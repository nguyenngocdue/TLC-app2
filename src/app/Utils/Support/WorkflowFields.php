<?php

namespace App\Utils\Support;

use App\Utils\Support\Json\IntermediateProps;
use App\Utils\Support\Json\SuperWorkflows;
use Illuminate\Support\Facades\Log;

class WorkflowFields
{
    static $type;
    static function getSuperWorkflowByRoleSet()
    {
        $roleSet = CurrentUser::getRoleSet();
        return SuperWorkflows::getFor(self::$type, $roleSet);
    }
    static function resolveSuperProps($superProps, $status, $type, $isCheckColumnStatus)
    {
        self::$type = $type;
        $buttonSave = true;
        $props = $superProps['props'];
        $definitions = $superProps['settings']['definitions'] ?? [];
        if (!$isCheckColumnStatus) {
            return [$status = null, [], $props, [], [], $buttonSave, []];
        }
        $statuses = $superProps['statuses'];
        if (!$status) {
            if (!empty($definitions)) {
                $status = 'new';
            } else {
                $status = array_shift(array_values($definitions));
            }
        }
        $hideSaveButton = $definitions['hide-save-btn'] ?? [];
        $buttonSave = !($status == 'closed') && !in_array($status, $hideSaveButton);
        $intermediate = $superProps['intermediate'];
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
        $transitions = $statuses[$status]['transitions'];

        $actionButtons = [];
        foreach ($transitions as $value) {
            $actionButtons[$value] = $statuses[$value]['action-buttons'];
        }
        if (CurrentUser::isAdmin()) {
            $buttonSave = true;
        }
        $workflows = self::getSuperWorkflowByRoleSet()['workflows'];
        $checkWorkflowCapa = [];
        foreach ($workflows as $key => $value) {
            $checkWorkflowCapa[$key] = $value['capabilities'];
        }
        if (!$checkWorkflowCapa[$status]) {
            $buttonSave = false;
            $actionButtons = [];
        }
        return [$status, $statuses, $props, $actionButtons, $transitions, $buttonSave, $propsIntermediate];
    }
    static function parseFields($props, $values, $defaultValues, $status, $isCheckColumnStatus)
    {
        $result = [];
        if ($isCheckColumnStatus && $status) {
            $workflow = self::getSuperWorkflowByRoleSet()['workflows'][$status];
            $visible = $workflow['visible'];
            $readonly = $workflow['readonly'];
            $required = $workflow['required'];
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
        $columnName = $prop['column_name'];
        $result['columnName'] = $columnName;
        $result['new_line'] = $prop['new_line'];
        $columnType = $prop['column_type'];
        $result['columnType'] = $columnType;
        $result['align'] = $prop['align'] ?? 'left';
        $control = $prop['control'];
        $result['control']  = $control;
        $col_span = $prop['col_span'] === '' ? 12 : $prop['col_span'] * 1;
        $result['col_span'] = $col_span;
        $result['classColSpanLabel'] = "col-span-" . ($prop['new_line'] === 'true' ? "12" : (24 / $col_span));
        $result['classColStart'] = "col-start-" . (24 / $col_span + 1);
        $result['classColSpanControl'] = "col-span-" . ($prop['new_line'] === 'true' ? "12" : (12 - 24 / $col_span));

        $result['value'] = $values->{$columnName} ?? '';
        $result['title'] = $columnName . " / " . $control;

        $defaultValue = $defaultValues[$key] ?? [];
        $result['labelExtra'] = $defaultValue['label_extra'] ?? "";
        $result['placeholder'] = $defaultValue['placeholder'] ?? "";
        $result['controlExtra'] = $defaultValue['control_extra'] ?? "";

        $realtime = $realtimes[$key] ?? [];
        $result['realtimeType'] = $realtime["realtime_type"] ?? "";
        $result['realtimeFn'] = $realtime["realtime_fn"] ?? "";

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
        $result['isRequired'] = in_array($prop['name'], $required) ? in_array("required", explode("|", $defaultValue['validation'] ?? "")) : "";
    }
}
