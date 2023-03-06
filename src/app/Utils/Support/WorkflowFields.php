<?php

namespace App\Utils\Support;

use App\Utils\Support\Json\SuperWorkflows;

class WorkflowFields
{
    static function getSuperWorkflowByRoleSet($type)
    {
        $roleSet = CurrentUser::getRoleSet();
        return SuperWorkflows::getFor($type, $roleSet);
    }
    static function parseFields($key, $prop, $values, $defaultValues, $status, $type)
    {
        $result = null;
        if ($status) {
            $workflow = self::getSuperWorkflowByRoleSet($type)['workflows'][$status];
            $visible = $workflow['visible'];
            $readonly = $workflow['readonly'];
            $required = $workflow['required'];
            $hidden = $workflow['hidden'];
            if (in_array($prop['name'], $visible)) {
                self::formatResult($result, $prop, $values, $key, $defaultValues, $hidden, $readonly, $required, true);
            }
        } else {
            self::formatResult($result, $prop, $values, $key, $defaultValues);
        }
        return $result;
    }
    static function formatResult(&$result, $prop, $values, $key, $defaultValues, $hidden = null, $readonly = null, $required = null, $typeWorkflow = false)
    {
        $result['label'] = $prop['label'];
        $columnName = $prop['column_name'];
        $result['columnName'] = $columnName;
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
        $result['readOnly'] = ($prop['read_only'] ?? false) === 'true';
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
