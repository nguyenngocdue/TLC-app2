<?php

namespace App\View\Components\Renderer\Report;

use App\Http\Controllers\Reports\TraitLibPivotTableDataFields2;
use App\Http\Controllers\Workflow\LibPivotTables2;
use App\Utils\Support\DateReport;
use App\Utils\Support\Report;
use App\Utils\Support\PivotReport;
use DateTime;


trait  PivotReportColumn3
{
    use TraitLibPivotTableDataFields2;

    public static function insertColumns($rowFields, $insColRowField, $originalCol)
    {
        //Insert column from admin
        if ($insColRowField) {
            foreach ($insColRowField as $field => $valInsRowField) {
                $index = isset($valInsRowField->insert_at_index) ? ($index = $valInsRowField->insert_at_index) : count($rowFields);
                $array = [
                    'title' => $valInsRowField->title ?? ucfirst(substr($field, 0, strpos($field, '_'))),
                    'dataIndex' => $valInsRowField->value_index,
                    'width' => $valInsRowField->width ?? 140,
                    'align' => $valInsRowField->align ?? 'left',
                    "footer" => $valInsRowField->footer ?? '',
                    'fixed' => $valInsRowField->fixed ?? null,
                ];
                array_splice($originalCol, $index, 0, [$array]);
            }
        }
        return $originalCol;
    }

    private function makeHeadColumn($rowFields, $insColRowField)
    {
        $columnsData = [];
        if (is_null($rowFields)) return [];
        foreach ($rowFields as $field => $value) {
            if (!$field) continue;
            if (is_object($value)) {
                $dataIndex = isset($value->column) ? $field . '_' . str_replace('.', '_', $value->column) : $field;
                $columnsData[] = [
                    'title' => $value->title ?? ucfirst(substr($field, 0, strpos($field, '_'))),
                    'dataIndex' => $dataIndex,
                    'width' => $value->width ?? 140,
                    'align' => $value->align ?? 'left',
                    "footer" => $value->footer ?? '',
                    'fixed' => $value->fixed ?? null,
                ];
            }
        }
        //Insert column from admin
        $columnsData = self::insertColumns($rowFields, $insColRowField, $columnsData);
        return $columnsData;
    }

    private static  function removeNumberedString($title)
    {
        $pattern = '/\[\d+\]_/';
        return preg_replace($pattern, "", $title);
    }

    protected function editRowHeaderColumnFields($groups, $columnFields)
    {
        $array = [];
        foreach ($groups as $key => $fields) {
                $thirdUnderscore = PivotReport::findPosition($field, '_', 3);
                if ($thirdUnderscore) {
                    if ($typeRender === 'type_2') {
                        $date = DateReport::formatDateString(substr($field, 0, $thirdUnderscore - 1), 'd/m/Y');
                        $dayOfWeek = DateReport::getShortDayOfWeek($date);
                        $array[$field] = "<div class='text-gray-700 dark:text-gray-300'><span>$date<br>$dayOfWeek</span></div>";
                    } else {
                        $date = DateReport::formatDateString(substr($field, 0, $thirdUnderscore - 1), 'd/m/y');
                        if ($date) $array[$field] = str_replace('/', '<br/>', $date);
                    }
                // dd($topTitleColumns);

                // $k = substr($field, 9);
                // $cols = $columnFields[$f];
                // if ($k === $f . '_' . $cols->value_index) {
                //     // $title = ucwords(substr($field, strpos($field, "_") + 1, strrpos($field, "_") - strpos($field, "_") - 1));
                //     $title = ucwords(substr($field, strpos($field, "_") + 1));
                //     if (str_contains($title, '[')) {
                //         $title = self::removeNumberedString($title);
                //     }
                //     $title = $topTitleColumns[$field] ?? 'Empty Header';
                //     $array[$field] = $title;
                // }
            }
        };
        return $array;
    }

    protected function editRowHeaderColumnFields2($arrayInfo, $columnFields)
    {
        $data = [];
        foreach (array_keys($arrayInfo) as $key) {
            $title = explode('_', $key)[1] ?? '';
            if (count($columnFields) > 2) {
                $title = array_slice($x = explode('_', $key), count($x) - 1, 1)[0]
                    . '<br/> (' . array_slice($x = explode('_', $key), count($x) - 2, 1)[0] . ')';
            }
            $data[$key] = $title;
        }
        return $data;
    }


    private function sortDates($a, $modeType)
    {
        dd($a);
        $result = [];
        $lib = LibPivotTables2::getFor($modeType);
        $keysOfColumnFields = array_keys(array_filter($lib['column_fields'], function ($item) {
            return (isset($item->hidden) && !$item->hidden);
        })) ?? [];
        $keysOfColumnFields = PivotReport::markDuplicatesAndGroupKey($keysOfColumnFields);
        $result = [];
        foreach ($keysOfColumnFields as $key => $values) {
            foreach ($values as $value) {
                foreach ($a as $item) {
                    $thirdUnderscore = PivotReport::findPosition($item, '_', 3);
                    $substr = substr($item, $thirdUnderscore,  strlen($value));
                    if ($substr === $value) {
                        $result[$value][] = $item;
                    }
                }
            }
        };
        $result = array_unique(array_merge(...array_values($result)));

        usort($result, function ($date1, $date2) {
            $thirdUnderscore = PivotReport::findPosition($date1, '_', 3);
            $dateTime1 = DateTime::createFromFormat('d_m_y', substr($date1, 0, $thirdUnderscore - 1));
            $dateTime2 = DateTime::createFromFormat('d_m_y', substr($date2, 0, $thirdUnderscore - 1));
            return $dateTime1 <=> $dateTime2;
        });
        $result = ['date' => $result];

        $otherItems = array_diff($a, array_merge(...array_values($result)));
        $result['other'] = $otherItems;
        return  $result;
    }

    private function makeColumnsOfColumnFields2($dataOutput, $columnFields)
    {
        $tableDataHeader = [];
        $columnsOfColumnFields = [];

        if (isset($dataOutput[0]['info_column_field'])) {
            $info = $dataOutput[0]['info_column_field'];
            $arrayInfo = array_merge(...array_merge(...array_values($info)));
            // dd($info);
            foreach ($arrayInfo as $key => $value) {
                $title = substr($key, 0, strpos($key, '_'));
                $indexTitle = $info[$title];
                $columnsOfColumnFields[] = [
                    'title' => ucwords($title),
                    'dataIndex' =>  $key,
                    'align' => is_numeric($value) ? 'right' : 'left',
                    'colspan' => count($indexTitle),
                ];
            }
            $tableDataHeader = $this->editRowHeaderColumnFields2($arrayInfo, $columnFields);
        }

        return [$tableDataHeader, $columnsOfColumnFields];
    }

    private static function moveStringToBottom($array)
    {
        $matchingElements = [];
        $otherElements = [];

        foreach ($array as $element) {
            if (preg_match('/(\[([2-9]|[1-9][0-9]|100)\])/', $element)) {
                $matchingElements[] = $element;
            } else {
                $otherElements[] = $element;
            }
        }
        usort($matchingElements, function ($a, $b) {
            $pattern = '/\[(\d+)\]/';
            preg_match($pattern, $a, $matchesA);
            preg_match($pattern, $b, $matchesB);
            $numberA = intval($matchesA[1]);
            $numberB = intval($matchesB[1]);
            return $numberA - $numberB;
        });

        return array_merge($otherElements, $matchingElements);
    }

    private function sortColumnsNeedToRender($data, $titleOrders)
    {
        usort($data, function ($a, $b) use ($titleOrders) {
            $titleA = strtolower($a['title']);
            $titleB = strtolower($b['title']);

            $indexA = array_search($titleA, $titleOrders);
            $indexB = array_search($titleB, $titleOrders);

            return $indexA - $indexB;
        });
        return $data;
    }

    private function filterValuesByKeyIndex($data, $targetValue, $keyIndex = 'value_index')
    {
        $matchingItems = [];
        foreach ($data as $key => $item) {
            if (!isset($item->$keyIndex)) continue;
            if ($key . '_' . $item->$keyIndex === $targetValue) {
                $matchingItems[] = $item;
            }
        }
        return $matchingItems;
    }

    private function tableDataHeadersContainedColumnFields($tableColumns, $tableDataHeaders)
    {
        foreach (array_keys($tableDataHeaders) as $field) {
            $dataFilter = array_column($tableColumns, 'dataIndex');
            if (!in_array($field, $dataFilter)) {
                unset($tableDataHeaders[$field]);
            }
        }
        return $tableDataHeaders;
    }



    private function makeColumnsOfColumnFields($linesData, $dataOutput, $libs, $modeType)
    {
        $keysOfColumnFields = array_keys($libs['column_fields']);
        $columnFields = $libs['column_fields'];
        $rowFields = $libs['row_fields'];

        $allColumns = [];
        foreach ($dataOutput as $value) $allColumns = array_unique(array_merge($allColumns, array_keys($value)));
        if (is_object($linesData)) $linesData = array_map(fn ($item) => (array)$item, $linesData->toArray());

        $lastItemDataSource = key(array_slice($linesData[0] ?? [], -1));
        $endArray = Report::retrieveDataByIndex($allColumns, $lastItemDataSource, false, 'value');
        dd($allColumns, $lastItemDataSource);
        $fields = $this->sortDates($endArray, $modeType);
        // $topTitleColumns = array_merge(...array_column($dataOutput, 'top_title_column'));
        // dd($fields);

        $columnsOfColumnFields = [];
        $columnsNeedToRender = array_keys(array_filter($columnFields, function ($item) {
            return (isset($item->hidden) && !$item->hidden || !isset($item->hidden));
        }));
    dd($fields);
        if ($rowFields) {
            $tableDataHeader = [];
            array_walk(
                $fields,
                function ($items, $key) use (&$columnsOfColumnFields, $columnFields, $tableDataHeader, $columnsNeedToRender) {
                    if ($key === 'other') {
                        //group items to count columns for setting span
                        $groupItems = PivotReport::groupSimilarStrings($items, $columnsNeedToRender, $columnFields);
                        // dd($fields);
                        foreach ($items as $value) {
                            // get ColumnFields need to render
                            $checkField =  PivotReport::isStringInItemsOfArray($columnsNeedToRender, $value);
                            if ($checkField) {
                                foreach ($groupItems as $keyGroup => $groups) {
                                    // dd($groupItems);
                                    //get info of column field from $keyGroup
                                    $cols = $columnFields[$keyGroup];
                                    $title = $cols->title ?? 'Empty tile Column';
                                    $width = $cols->width ?? '50';
                                    $align = $cols->align ?? 'center';
                                    $countRenderCol = count($groups) ?? 1;

                                    $columnsOfColumnFields[] = [
                                        'title' => $title ?? 'Empty',
                                        'dataIndex' => $value,
                                        'width' => $width ?? 50,
                                        'colspan' =>  $countRenderCol,
                                        'header_name' => $tableDataHeader[$value] ?? '',
                                        'align' =>  $align,

                                    ];
                                    if(isset($cols->hasHeaderTable) && $cols->hasHeaderTable){
                                        dd($groups);
                                        $tableDataHeader = $this->editRowHeaderColumnFields($groups,$cols);
                                    }

                                }
                            }
                        }
                    } else {
                        $items = self::moveStringToBottom($items);
                        $groupItems = PivotReport::groupItemsByString($items, 'end');
                        foreach ($groupItems as $key => $dates) {
                            $attributes = $this->filterValuesByKeyIndex($columnFields, $key);
                            if (!$attrs = reset($attributes)) continue;
                            foreach ($dates as $value) {
                                $columnsOfColumnFields[] = [
                                    'title' => $attrs->title ??  'Empty Title',
                                    'dataIndex' => $value,
                                    'align' => $attrs->align ?? 'left',
                                    'width' => $attrs->width ?? 50,
                                    'colspan' => count($dates),
                                    "footer" => $attrs->footer ?? '',
                                ];
                            }
                        }
                    }
                }
            );
        } else {
            [$tableDataHeader, $columnsOfColumnFields] = $this->makeColumnsOfColumnFields2($dataOutput, $columnFields);
        }
        // sort column fields follow table header

        $columnsOfColumnFields = Report::sortByKey($columnsOfColumnFields, 'header_name');
        $tableDataHeader = $this->tableDataHeadersContainedColumnFields($columnsOfColumnFields, $tableDataHeader);
        // dump($columnsOfColumnFields, $tableDataHeader);
        return [$tableDataHeader, $columnsOfColumnFields];
    }

    private function makeColumnsOfAgg($dataAggregations)
    {
        $columnsOfAgg = [];
        if (!$dataAggregations) return [];
        foreach ($dataAggregations as $key => $value) {
            $columnsOfAgg[] = [
                'title' => $value->title ?? str_replace('_', ' ', $key),
                'dataIndex' => $value->aggregation . '_' . $key,
                'width' => $value->width ?? 50,
                'align' => $value->align ?? 'right',
                'footer' => $value->footer ?? '',
            ];
        };
        return $columnsOfAgg;
    }

    public function makeColumnsRenderer($linesData, $dataOutput, $libs, $modeType)
    {
        $rowFields = $libs['row_fields'];
        $insColRowField = $libs['insert_column_row_fields'];
        $aggregations = $libs['data_fields'];
        // dd($dataOutput);

        if (empty($dataOutput)) return [[], []];
        if (!$this->getDataFields($modeType)) return false;
        $columnsOfRowFields = $this->makeHeadColumn($rowFields, $insColRowField);
        [$tableDataHeader, $columnsOfColumnFields] = $this->makeColumnsOfColumnFields($linesData, $dataOutput, $libs, $modeType);
        $columnsOfAgg = $this->makeColumnsOfAgg($aggregations);
        $tableColumns = array_merge($columnsOfRowFields, $columnsOfColumnFields, $columnsOfAgg);
        // dd($columnsOfColumnFields);
        return [$tableDataHeader, $tableColumns];
    }
}
