<?php

namespace App\View\Components\Renderer\Table;

use App\Utils\Constant;
use App\Utils\Support\DateTimeConcern;
use Carbon\Carbon;

trait TableTraitFooter
{
    function makeOneFooter($column, $tableName, $dataSource)
    {
        $debug = !true;
        $tz = DateTimeConcern::getTz();
        $aggList = [
            'agg_none',
            'agg_count_all',
            'agg_sum',
            'agg_avg',
            'agg_median',
            'agg_min',
            'agg_max',
            'agg_range',

            // 'agg_count_values',
            // 'agg_count_unique_values',
            // 'agg_count_empty',
            // 'agg_count_not_empty',
            // 'agg_percent_empty',
            // 'agg_percent_not_empty',
        ];
        $fieldName = $column['dataIndex'];
        $control = $column['properties']['control'] ?? "";

        $items = $dataSource->map(fn ($item) => is_object($item[$fieldName]) ? $item[$fieldName]->value : $item[$fieldName]);

        $result['agg_none'] = '';
        $result['agg_count_all'] = $items->count();

        $result['agg_sum'] = 0;
        $result['agg_avg'] = 0;
        $result['agg_median'] = 0;
        $result['agg_min'] = 0;
        $result['agg_max'] = 0;
        $result['agg_range'] = 0;

        if (in_array($control, ['picker_date', 'picker_datetime'])) {
            $items = $items->map(fn ($item) => Carbon::parse($item)->diffInSeconds('1970-01-01 00:00:00'));
        }
        if (in_array($control, ['picker_time'])) {
            $items = $items->map(fn ($item) => Carbon::parse($item)->diffInSeconds('00:00:00'));
        }
        if (in_array($control, ['picker_month', 'picker_week', 'picker_quarter'])) {
            $items = collect([]); //TO BE IMPLEMENTED
        }
        if (in_array($control, ['picker_year'])) {
            $items = $items->map(fn ($item) => $item ? substr($item, 0, 4) : 0); //<< Incase year is "", make it 0
        }

        // dump($items);
        $result['agg_sum'] = $items->sum();
        $result['agg_avg'] = $items->avg();
        $result['agg_median'] = $items->median();
        $result['agg_min'] = $items->min();
        $result['agg_max'] = $items->max();
        $result['agg_range'] = $result['agg_max'] - $result['agg_min'];

        if (in_array($control, ['picker_date', 'picker_datetime', 'picker_time'])) {
            $timestampSum = $result['agg_sum'];
            $timestampAvg = $result['agg_avg'];
            $timestampMin = $result['agg_min'];
            $timestampMax = $result['agg_max'];
            $timestampMedian = $result['agg_median'];
            // $timestampRange = $result['agg_range'];
            if ($control == 'picker_date') {
                $result['agg_avg'] = Carbon::createFromTimestamp($timestampAvg)->format(Constant::FORMAT_DATE_ASIAN);
                $result['agg_min'] = Carbon::createFromTimestamp($timestampMin)->format(Constant::FORMAT_DATE_ASIAN);
                $result['agg_max'] = Carbon::createFromTimestamp($timestampMax)->format(Constant::FORMAT_DATE_ASIAN);
                $result['agg_median'] = Carbon::createFromTimestamp($timestampMedian)->format(Constant::FORMAT_DATE_ASIAN);
                $result['agg_sum'] = "maybe_meaningless";
                // $result['agg_range'] = $timestampRange / (24 * 3600);
            }
            if ($control == 'picker_datetime') {
                $result['agg_avg'] = Carbon::createFromTimestamp($timestampAvg)->setTimezone($tz)->format(Constant::FORMAT_DATETIME_ASIAN);
                $result['agg_min'] = Carbon::createFromTimestamp($timestampMin)->setTimezone($tz)->format(Constant::FORMAT_DATETIME_ASIAN);
                $result['agg_max'] = Carbon::createFromTimestamp($timestampMax)->setTimezone($tz)->format(Constant::FORMAT_DATETIME_ASIAN);
                $result['agg_median'] = Carbon::createFromTimestamp($timestampMedian)->setTimezone($tz)->format(Constant::FORMAT_DATETIME_ASIAN);
                $result['agg_sum'] = "maybe_meaningless";
                // $result['agg_range'] = $timestampRange / (24 * 3600);
            }
            if ($control == 'picker_time') {
                $result['agg_avg'] = Carbon::createFromTimestamp($timestampAvg)->format(Constant::FORMAT_TIME);
                $result['agg_min'] = Carbon::createFromTimestamp($timestampMin)->format(Constant::FORMAT_TIME);
                $result['agg_max'] = Carbon::createFromTimestamp($timestampMax)->format(Constant::FORMAT_TIME);
                $result['agg_median'] = Carbon::createFromTimestamp($timestampMedian)->format(Constant::FORMAT_TIME);
                $result['agg_sum'] = Carbon::createFromTimestamp($timestampSum)->format(Constant::FORMAT_TIME);
            }
        }

        $class = "focus:outline-none border-0 bg-transparent w-full h-6 block text-right pr-6 py-0";
        $inputs = [];
        $footer = $column['footer'];
        // echo $footer;
        foreach ($aggList as $agg) {
            $bg = ($footer == $agg) ? "text-blue-700" : ($debug ? "" : "hidden");
            $id = "{$tableName}[footer][{$fieldName}][$agg]";
            // $value = ($agg != 'agg_none') ? round($result[$agg], 2)  : "";
            $value = $result[$agg];
            $onChange = "onChangeDropdown4AggregateFromTable111('$id', this.value)";
            $inputs[] = "<input id='$id' title='$agg' component='TraitFooter' value='$value' readonly class='$class $bg' onChange=\"$onChange\" />";
        }
        return join("", $inputs);
    }

    function makeFooter($columns, $tableName, $dataSource)
    {
        $result0 = [];
        $hasFooter = false;
        foreach ($columns as $column) {
            if (isset($column['invisible']) && $column['invisible']) continue;
            if (isset($column['footer'])) {
                $hasFooter = true;
                $result0[$column['dataIndex']] = $this->makeOneFooter($column, $tableName, $dataSource);
            } else {
                if (isset($column['dataIndex'])) $result0[$column['dataIndex']] = "";
            }
        }
        if (!$hasFooter) return;

        $result0 = array_map(fn ($c) => "<td>" . $c . "</td>", $result0);
        // dump($result0);
        return join("", $result0);
    }
}
