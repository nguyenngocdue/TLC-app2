<?php

namespace App\Utils\Support;

use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\Json\SuperDefinitions;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\Json\SuperWorkflows;
use Illuminate\Support\Facades\Log;

class WorkflowFields
{
    public static $type;
    private static function getSuperWorkflowByRoleSet()
    {
        $roleSet = CurrentUser::getRoleSet();
        return SuperWorkflows::getFor(self::$type, $roleSet);
    }
    // public static function getNewFromDefinitions($type)
    // {
    //     $superProps = SuperProps::getFor($type);
    //     $definitions = $superProps['settings']['definitions'] ?? [];
    //     if (empty($definitions)) {
    //         $status = 'new';
    //     } else {
    //         $def = array_values($definitions);
    //         $status = array_shift($def)[0];
    //     }
    //     return $status;
    // }
    private static function getStatusFollowDefinitions($status)
    {
        if (!$status) $status = SuperDefinitions::getNewOf(static::$type);
        // if (!$status) $status = self::getNewFromDefinitions(static::$type);
        return $status;
    }
    private static function getPropsIntermediate($intermediate, $status, &$props)
    {
        $propsIntermediate = [];
        if (!empty($intermediate) && isset($intermediate[$status])) {
            $filters = $intermediate[$status];
            foreach ($filters as $key => $items) {
                foreach ($items as $value) {
                    $propsIntermediate[$key][$value] = $props[$value];
                    unset($props[$value]);
                }
            }
        }
        return $propsIntermediate;
    }
    private static function checkButtonSaveRenderWhenAdmin(&$buttonSave)
    {
        if (CurrentUser::isAdmin()) {
            $buttonSave = true;
        }
    }
    private static function getCapabilitiesByRoleSetCurrentUser()
    {
        $workflows = self::getSuperWorkflowByRoleSet()['workflows'];
        $checkWorkflowCapa = [];
        foreach ($workflows as $key => $value) {
            $checkWorkflowCapa[$key] = $value['capabilities'] ?? false;
        }
        return $checkWorkflowCapa;
    }
    private static function checkWorkflowCapabilitiesForRenderButton($checkWorkflowCapa, $status, &$buttonSave, &$actionButtons)
    {
        if (isset($checkWorkflowCapa[$status]) && !$checkWorkflowCapa[$status]) {
            $buttonSave = false;
            $actionButtons = [];
        }
    }
    private static function getActionButtonsFromTransitionAndStatuses($transitions, $statuses, $closed, $ownerId, $type)
    {
        $actionButtons = [];
        $isTree = LibApps::getFor($type)['apply_approval_tree'] ?? false;
        foreach ($transitions as $value) {
            $actionButtons[$value] = $statuses[$value]['action-buttons'] ?? [];
            $actionButtons[$value]['color'] = $statuses[$value]['color'];
            $actionButtons[$value]['color_index'] = $statuses[$value]['color_index'];
            foreach ($closed as $close) {
                $value == $close ? $actionButtons[$value]['closed_at'] = true : $actionButtons[$value]['closed_at'] = false;
                if ($isTree && !CurrentUser::isAdmin()) {
                    if ($value == $close) {
                        $ownerId == static::ownerIdLogin()
                            ? $actionButtons[$value]['is_close'] = true
                            : $actionButtons[$value]['is_close'] = false;
                    } else {
                        $actionButtons[$value]['is_close'] = false;
                    }
                } else {
                    $actionButtons[$value]['is_close'] = false;
                }
            }
        }
        return $actionButtons;
    }
    private static function ownerIdLogin()
    {
        return auth()->user()->id;
    }
    public static function resolveSuperProps($superProps, $status, $type, $isCheckColumnStatus, $ownerId)
    {
        self::$type = $type;
        $buttonSave = true;
        $props = $superProps['props'];
        $definitions = $superProps['settings']['definitions'] ?? [];
        if (!$isCheckColumnStatus) {
            return [$status = null, [], $props, [], [], $buttonSave, []];
        }
        $statuses = $superProps['statuses'];
        $status = self::getStatusFollowDefinitions($status);

        $hideSaveButton = $definitions['hide-save-btn'] ?? [];
        $closed = $definitions['closed'] ?? [];
        $buttonSave = !(in_array($status, $closed)) && !in_array($status, $hideSaveButton);
        $intermediate = $superProps['intermediate'];
        $propsIntermediate = self::getPropsIntermediate($intermediate, $status, $props);
        $transitions = $statuses[$status]['transitions'] ?? [];
        $actionButtons = self::getActionButtonsFromTransitionAndStatuses($transitions, $statuses, $closed, $ownerId, $type);
        self::checkButtonSaveRenderWhenAdmin($buttonSave);
        $checkWorkflowCapa = self::getCapabilitiesByRoleSetCurrentUser();
        self::checkWorkflowCapabilitiesForRenderButton($checkWorkflowCapa, $status, $buttonSave, $actionButtons);
        return [$status, $statuses, $props, $actionButtons, $transitions, $buttonSave, $propsIntermediate];
    }
    public static function parseFields($props, $values, $defaultValues, $status, $isCheckColumnStatus)
    {
        $result = [];
        if ($isCheckColumnStatus && $status && isset(self::getSuperWorkflowByRoleSet()['workflows'][$status])) {
            $workflow = self::getSuperWorkflowByRoleSet()['workflows'][$status];
            $visible = $workflow['visible'];
            $readonly = $workflow['readonly'];

            $required = [];
            foreach ($defaultValues as $propName => $prop) {
                $validation = $prop['validation'];
                $isRequired = in_array('required', explode("|", $validation));
                if ($isRequired) $required[] = $propName;
            }
            $required = array_unique(array_merge($required, $workflow['required']));

            $hidden = $workflow['hidden'];
            foreach ($props as $key => $prop) {
                if (in_array($prop['name'], $visible)) {
                    self::formatResult($result[$key], $prop, $values, $key, $defaultValues, $hidden, $readonly, $required, true);
                }
            }
        } else {
            foreach ($props as $key => $prop) {
                self::formatResult($result[$key], $prop, $values, $key, $defaultValues);
            }
        }
        return $result;
    }
    static function formatResult(&$result, $prop, $values, $key, $defaultValues, $hidden = null, $readonly = null, $required = null, $typeWorkflow = false)
    {
        $result['label'] = $prop['label'];
        $result['properties'] = $prop['properties'];
        $columnName = $prop['column_name'];
        $result['columnName'] = $columnName;
        $result['numericScale'] = $prop['numeric_scale'];
        $result['new_line'] = $prop['new_line'];
        $columnType = $prop['column_type'];
        $result['columnType'] = $columnType;
        $result['align'] = $prop['align'] ?? 'left';
        $control = $prop['control'];
        $result['control']  = $control;
        $col_span = $prop['col_span'] === '' ? 12 : $prop['col_span'] * 1;
        $result['col_span'] = $col_span;
        $result['classColSpanLabel'] = "col-span-" . ($prop['new_line'] === 'true' ? "12" : (24 / $col_span));
        $result['classColStart'] = ""; //"col-start-" . (24 / $col_span + 1);
        $result['classColSpanControl'] = "col-span-" . ($prop['new_line'] === 'true' ? "12" : (12 - 24 / $col_span));
        $result['value'] = $values->{$columnName} ?? '';
        $result['title'] = $columnName . " / " . $control;
        $defaultValue = $defaultValues[$key] ?? [];
        $result['default_value'] = $defaultValue['default_value'] ?? "";
        $result['labelExtra'] = $defaultValue['label_extra'] ?? "";
        $result['placeholder'] = $defaultValue['placeholder'] ?? "";
        $result['controlExtra'] = $defaultValue['control_extra'] ?? "";
        $result['textareaRows'] = $defaultValue['textarea_rows'] ?? 5;

        // $realtime = $realtimes[$key] ?? [];
        // $result['realtimeType'] = $realtime["realtime_type"] ?? "";
        // $result['realtimeFn'] = $realtime["realtime_fn"] ?? "";
        $result['iconJson'] = $columnType === 'json' ? '<i title="JSON format" class="fa-duotone fa-brackets-curly"></i>' : "";

        $typeWorkflow ?  self::followWorkflow($result, $prop, $defaultValue, $hidden, $readonly, $required) : self::noneFollowWorkflow($result, $prop, $defaultValue);
    }
    private static function noneFollowWorkflow(&$result, $prop, $defaultValue)
    {
        $result['hiddenRow'] = $prop['hidden_edit'] === 'true' ? "hidden" : "";
        $result['hiddenLabel'] = $prop['hidden_label'] === 'true';
        $result['readOnly'] =  ($prop['read_only'] ?? false) === 'true';
        $result['isRequired'] = in_array("required", explode("|", $defaultValue['validation'] ?? ""));
    }
    private static function followWorkflow(&$result, $prop, $defaultValue, $hidden, $readonly, $required)
    {
        $result['hiddenRow'] = ($prop['hidden_edit'] ? $prop['hidden_edit'] : in_array($prop['name'], $hidden)) == 'true' ? "hidden" : "";
        $result['hiddenLabel'] = ($prop['hidden_label'] ? $prop['hidden_label'] : in_array($prop['name'], $hidden)) == 'true';
        $result['readOnly'] = ($prop['read_only'] ? $prop['read_only'] : in_array($prop['name'], $readonly)) == 'true';
        $result['isRequired'] = in_array($prop['name'], $required) ? true : in_array("required", explode("|", $defaultValue['validation'] ?? ""));
    }
}
