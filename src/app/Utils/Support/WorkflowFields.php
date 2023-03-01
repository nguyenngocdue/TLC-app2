<?php

namespace App\Utils\Support;

class WorkflowFields
{
    static function parseFields($key, $prop, $values, $defaultValues)
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
        $result['classColSpanLabel'] = "col-span-" . (24 / $col_span);
        $result['classColStart'] = "col-start-" . (24 / $col_span + 1);
        $result['classColSpanControl'] = "col-span-" . ($prop['new_line'] === 'true' ? "12" : (12 - 24 / $col_span));

        $result['value'] = $values->{$columnName} ?? '';
        $result['title'] = $columnName . " / " . $control;
        $result['hiddenRow'] = $prop['hidden_edit'] === 'true' ? "hidden" : "";
        $result['hiddenLabel'] = $prop['hidden_label'] === 'true';
        $result['readOnly'] = ($prop['read_only'] ?? false) === 'true';

        $result['defaultValue'] = $defaultValues[$key] ?? [];
        $result['labelExtra'] = $defaultValue['label_extra'] ?? "";
        $result['placeholder'] = $defaultValue['placeholder'] ?? "";
        $result['controlExtra'] = $defaultValue['control_extra'] ?? "";

        $realtime = $realtimes[$key] ?? [];
        $result['realtimeType'] = $realtime["realtime_type"] ?? "";
        $result['realtimeFn'] = $realtime["realtime_fn"] ?? "";

        $result['isRequired'] = in_array("required", explode("|", $defaultValue['validation'] ?? ""));
        $result['iconJson'] = $columnType === 'json' ? '<i title="JSON format" class="fa-duotone fa-brackets-curly"></i>' : "";

        return $result;
    }
}
