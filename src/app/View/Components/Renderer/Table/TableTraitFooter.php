<?php

namespace App\View\Components\Renderer\Table;

use App\Utils\Constant;
use App\Utils\Support\DateTimeConcern;
use App\View\Components\Controls\RelationshipRenderer2;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

trait TableTraitFooter
{
    private $debugFooter = !true;
    private $aggList = [
        'agg_none',
        'agg_count_all',
        'agg_sum',
        'agg_avg',
        'agg_median',
        'agg_min',
        'agg_max',
        'agg_range',
        'agg_count_unique_values',
        'agg_unique_values',

        // 'agg_count_values',
        // 'agg_count_empty',
        // 'agg_count_not_empty',
        // 'agg_percent_empty',
        // 'agg_percent_not_empty',
    ];

    function makeOneFooterRaw($fieldName, $control, $eloquentTable, $dataSource, $needFormatNumber)
    {
        if (!$dataSource) return;

        $tz = DateTimeConcern::getTz();

        // $items = $dataSource->map(fn ($item) => is_object($item[$fieldName]) ? $item[$fieldName]->value : $item[$fieldName]);
        $items = $dataSource->map(function ($item) use ($fieldName) {
            if (is_object($item)) {
                return is_object($item->$fieldName) ? $item->$fieldName->value : $item->$fieldName;
            }
            if (is_array($item)) {
                return is_object($item[$fieldName]) ? $item[$fieldName]->value : $item[$fieldName];
            }
            return "-111111";
        });

        $result['agg_none'] = '';
        $result['agg_count_all'] = $items->count();
        $result['agg_count_unique_values'] = $items->unique()->count();

        $result['agg_sum'] = 0;
        $result['agg_avg'] = 0;
        $result['agg_median'] = 0;
        $result['agg_min'] = 0;
        $result['agg_max'] = 0;
        $result['agg_range'] = 0;

        if (in_array($control, ['picker_date', 'picker_datetime'])) {
            // $items = $items->map(fn ($item) => $item ? Carbon::parse($item)->diffInSeconds('1970-01-01 00:00:00') : 0);
            $items = $items->map(fn ($item) => $item ? Carbon::parse(DateTimeConcern::convertForSaving($control, $item))->diffInSeconds('1970-01-01 00:00:00') : 0);
        }
        if (in_array($control, ['picker_time'])) {
            $items = $items->map(fn ($item) => $item ? Carbon::parse(DateTimeConcern::convertForSaving($control, $item))->diffInSeconds('00:00:00') : 0);
        }
        if (in_array($control, ['picker_month', 'picker_week', 'picker_quarter'])) {
            $items = collect([]); //TO BE IMPLEMENTED
        }
        if (in_array($control, ['picker_year'])) {
            $items = $items->map(fn ($item) => $item ? substr($item, 0, 4) : 0); //<< Incase year is a empty string, make it 0
        }

        if ($fieldName == 'status') {
            $result['agg_unique_values'] = '"' . $items->unique()->join(";") . '"';
            return $result;
        };
        // dump($items);
        try {
            // $result['agg_sum'] = $items->sum();
            $items = $items->filter(fn ($i) => is_numeric($i));
            $result['agg_sum'] = $items->map(fn ($item) => (float) str_replace(',', '', $item))->sum();
            $result['agg_avg'] = ($a = $items->avg()) ? $a : null;
            $result['agg_median'] = ($a = $items->median()) ? $a : null;
            $result['agg_min'] = ($a = $items->min()) ? $a : null;
            $result['agg_max'] = ($a = $items->max()) ? $a : null;
            $result['agg_range'] = $result['agg_max'] - $result['agg_min'];
        } catch (\Exception $e) {
            Log::error("Exception in Footer, Table [" . $eloquentTable . "] " . "field [" . $fieldName . "] " . $e->getMessage());
        }

        if (in_array($control, ['picker_date', 'picker_datetime', 'picker_time'])) {
            $timestampSum = $result['agg_sum'];
            $timestampAvg = $result['agg_avg'];
            $timestampMin = $result['agg_min'];
            $timestampMax = $result['agg_max'];
            $timestampMedian = $result['agg_median'];
            // $timestampRange = $result['agg_range'];
            switch ($control) {
                case "picker_date":
                    $result['agg_avg'] = ($timestampAvg) ?  Carbon::createFromTimestamp($timestampAvg)->format(Constant::FORMAT_DATE_ASIAN) : null;
                    $result['agg_min'] = ($timestampMin) ? Carbon::createFromTimestamp($timestampMin)->format(Constant::FORMAT_DATE_ASIAN) : null;
                    $result['agg_max'] = ($timestampMax) ? Carbon::createFromTimestamp($timestampMax)->format(Constant::FORMAT_DATE_ASIAN) : null;
                    $result['agg_median'] = ($timestampMedian) ? Carbon::createFromTimestamp($timestampMedian)->format(Constant::FORMAT_DATE_ASIAN) : null;
                    $result['agg_sum'] = "maybe_meaningless";
                    break;
                case "picker_datetime":
                    $result['agg_avg'] = ($timestampAvg) ? Carbon::createFromTimestamp($timestampAvg)->setTimezone($tz)->format(Constant::FORMAT_DATETIME_ASIAN) : null;
                    $result['agg_min'] = ($timestampMin) ? Carbon::createFromTimestamp($timestampMin)->setTimezone($tz)->format(Constant::FORMAT_DATETIME_ASIAN) : null;
                    $result['agg_max'] = ($timestampMax) ? Carbon::createFromTimestamp($timestampMax)->setTimezone($tz)->format(Constant::FORMAT_DATETIME_ASIAN) : null;
                    $result['agg_median'] = ($timestampMedian) ? Carbon::createFromTimestamp($timestampMedian)->setTimezone($tz)->format(Constant::FORMAT_DATETIME_ASIAN) : null;
                    $result['agg_sum'] = "maybe_meaningless";
                    break;
                case "picker_time":
                    $result['agg_avg'] = Carbon::createFromTimestamp($timestampAvg)->format(Constant::FORMAT_TIME);
                    $result['agg_min'] = Carbon::createFromTimestamp($timestampMin)->format(Constant::FORMAT_TIME);
                    $result['agg_max'] = Carbon::createFromTimestamp($timestampMax)->format(Constant::FORMAT_TIME);
                    $result['agg_median'] = Carbon::createFromTimestamp($timestampMedian)->format(Constant::FORMAT_TIME);
                    $result['agg_sum'] = Carbon::createFromTimestamp($timestampSum)->format(Constant::FORMAT_TIME);
                    break;
                default:
                    //
                    break;
            }
        } elseif ($needFormatNumber) {
            //needFormatNumber: if called during update2, it must not have comma, otherwise truncate exception
            $result['agg_sum'] = number_format($result['agg_sum'], 2);
            $result['agg_avg'] = number_format($result['agg_avg'], 2);
            $result['agg_median'] = number_format($result['agg_median'], 2);
            $result['agg_min'] = number_format($result['agg_min'], 2);
            $result['agg_max'] = number_format($result['agg_max'], 2);
            $result['agg_range'] = number_format($result['agg_range'], 2);
        }

        return $result;
    }
    private function makeOneFooter($result, $fieldName, $footer, $eloquentTable)
    {
        $class = "focus:outline-none border-0 bg-transparent h-6 text-right pr-2 py-0 w-full"; //<<w-full1: report will have wide columns
        $inputs = [];

        foreach ($this->aggList as $agg) {
            $bg = ($footer == $agg) ? "text-blue-700" : ($this->debugFooter ? "" : "hidden");
            $id = "{$eloquentTable}[footer][{$fieldName}][$agg]";
            // $value = ($agg != 'agg_none') ? round($result[$agg], 2)  : "";
            $value = $result[$agg] ?? "";
            $onChange = "onChangeDropdown4AggregateFromTable('$id', this.value)";
            $input = "<input id='$id' title='$agg' component='TraitFooter' value='$value' readonly class='$class $bg' onChange=\"$onChange\" />";
            if ($this->debugFooter) {
                $inputs[] = "<div class='text-right'>$agg: $input</div>";
            } else {
                $inputs[] = $input;
            }
        }

        $inputs = join("", $inputs);
        return $inputs;
    }

    function makeFooter($columns, $tableName, $dataSource)
    {
        try {
            $eloquentTables = RelationshipRenderer2::getCacheTable01NameToEloquent();
            $eloquentTable = $eloquentTables[$tableName] ?? "";
            $result0 = [];
            $hasFooter = false;
            foreach ($columns as $column) {
                if (isset($column['invisible']) && $column['invisible']) continue;
                if (isset($column['footer'])) {
                    $hasFooter = true;
                    if (in_array($column['footer'], $this->aggList)) {
                        $fieldName = $column['dataIndex'];
                        $control = $column['properties']['control'] ?? "";
                        $footer = $column['footer'];
                        // $fieldName = $column['dataIndex'];
                        $raw = $this->makeOneFooterRaw($fieldName, $control, $eloquentTable, $dataSource, true);
                        $inputs = $this->makeOneFooter($raw, $fieldName, $footer, $eloquentTable);
                        $result0[$column['dataIndex']] = $inputs;
                    } else {
                        $result0[$column['dataIndex']] = $column['footer'];
                    }
                } else {
                    if (isset($column['dataIndex'])) $result0[$column['dataIndex']] = "";
                }
            }
            if (!$hasFooter) return;

            $result0 = array_map(fn ($c) => "<td>" . $c . "</td>", $result0);
            // dump($result0);
            return join("", $result0);
        } catch (\Exception $e) {
            dump("Error during making footer aggregation, line " . $e->getLine());
            dd($e->getMessage());
        }
    }
}
