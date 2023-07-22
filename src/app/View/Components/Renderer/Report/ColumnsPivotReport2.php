<?php

namespace App\View\Components\Renderer\Report;


use App\Http\Controllers\TraitLibPivotTableDataFields2;
use App\Http\Controllers\Workflow\LibPivotTables;
use App\Http\Controllers\Workflow\LibPivotTables2;
use App\Utils\Support\Report;
use App\Utils\Support\PivotReport;
use Illuminate\Support\Str;
use DateTime;


trait  ColumnsPivotReport2
{
    use TraitLibPivotTableDataFields2;
    private function makeHeadColumn($rowFields)
    {
        $columnsData = [];
        if (is_null($rowFields)) return [];
        foreach ($rowFields as $key => $value) {
            if (!$key) continue;
            if (is_object($value)) {
                $dataIndex = isset($value->column)? $key . '_' . str_replace('.', '_', $value->column) : $key;
                $columnsData[] = [
                    'title' => $value->title ?? ucfirst(substr($key,0, strpos($key, '_'))),
                    'dataIndex' => $dataIndex,
                    'width' => 140,
                ];
            }
        }
        // dd($columnsData);
        return $columnsData;
    }

    private static  function removeNumberedString($title)
    {
        $pattern = '/\[\d+\]_/';
        return preg_replace($pattern, "", $title);
    }

    protected function editRowHeaderColumnFields($fieldGroups, $keysOfColumnFields, $topTitleColumns)
    {
        $array = [];
        foreach ($fieldGroups as $key => $fields) {
            if ($key !== 'other') {
                foreach ($fields as  $field) {
                    $thirdUnderscore = PivotReport::findPosition($field, '_', 3);
                    if ($thirdUnderscore) {
                        $date = Report::formatDateString(substr($field, 0, $thirdUnderscore - 1), 'd/m/y');
                        if ($date) $array[$field] = str_replace('/', '<br/>', $date);
                    }
                }
            } else {
                foreach ($fields as $field) {
                    $checkFiled =  PivotReport::isStringInItemsOfArray($keysOfColumnFields, $field);
                    if ($checkFiled) {
                        // $title = ucwords(substr($field, strpos($field, "_") + 1, strrpos($field, "_") - strpos($field, "_") - 1));

                        $title = ucwords(substr($field, strpos($field, "_") + 1));
                        if (str_contains($title, '[')) {
                            $title = self::removeNumberedString($title);
                        }
                        $title = $topTitleColumns[$field] ?? '';
                        $array[$field] = $title;
                    }
                }
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
        $result = [];
        $lib = LibPivotTables2::getFor($modeType);
        $keysOfColumnFields = array_keys($lib['column_fields']) ?? [];
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

        // dd($tableDataHeader, $columnsOfColumnFields);
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
            if ($key.'_'.$item->$keyIndex === $targetValue) {
                $matchingItems[] = $item;
            }
        }
        return $matchingItems;
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
        $fields = $this->sortDates($endArray, $modeType);
        // dd( $fields);
        $topTitleColumns = array_merge(...array_column($dataOutput, 'top_title_column'));

        $columnsOfColumnFields = [];
        if ($rowFields) {
            $tableDataHeader = $this->editRowHeaderColumnFields($fields, $keysOfColumnFields, $topTitleColumns);
            array_walk(
                $fields,
                function ($items, $key) use (&$columnsOfColumnFields, $columnFields, $tableDataHeader) {
                    if ($key === 'other') {
                        $groupItems = PivotReport::groupSimilarStrings($items);
                        foreach ($items as $value) {
                            $columnsNeedToRender = array_keys($columnFields);
                            // dd($columnFields, $columnsNeedToRender, $items);
                            $checkField =  PivotReport::isStringInItemsOfArray($columnsNeedToRender, $value);
                            if ($checkField) {
                                $title = '';
                                $countRenderCol = 1;
                                foreach ($groupItems as $keyGroup => $group) {
                                    if (str_contains($value, $keyGroup) && in_array($value, $group) && isset($columnFields[$keyGroup])) {
                                        $info = $columnFields[$keyGroup];
                                        $title = $info->title ?? 'Empty tile Column';
                                        $countRenderCol = count($group);
                                    }
                                }
                                $columnsOfColumnFields[] = [
                                    'title' => $title,
                                    'dataIndex' => $value,
                                    'align' => is_numeric($value) ? 'left' : 'right',
                                    'width' => 80,
                                    'colspan' =>  $countRenderCol,
                                    'header_name' => $tableDataHeader[$value] ?? '',
                                ];
                            };
                        }
                    } else {
                        $items = self::moveStringToBottom($items);
                        $groupItems = PivotReport::groupItemsByString($items, 'end');
                        foreach ($groupItems as $key => $dates) {
                            $attributes = $this->filterValuesByKeyIndex($columnFields, $key);
                            if (!$attrs = reset($attributes)) continue;
                            $title = $attrs->title ??  'Empty Title';
                            foreach ($dates as $value) {
                                $columnsOfColumnFields[] = [
                                    'title' =>  $title,
                                    'dataIndex' => $value,
                                    'align' => is_numeric($value) ? 'left' : 'right',
                                    'width' => 50,
                                    'colspan' => count($dates),
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
        usort($columnsOfColumnFields, function ($a, $b) {
            if (!isset($a['header_name'])) return [];
            return strcmp($a['header_name'], $b['header_name']);
        });
        return [$tableDataHeader, $columnsOfColumnFields];
    }

    private function makeColumnsOfAgg($dataAggregations)
    {
        $columnsOfAgg = [];
        if (!$dataAggregations) return [];
        foreach ($dataAggregations as $key => $value) {
            $columnsOfAgg[] = [
                'title' => $value->title ?? str_replace('_', ' ', $key),
                'dataIndex' => $value->aggregation.'_'. $key,
                'align' => 'right',
                'width' => 100,
            ];
        };
        return $columnsOfAgg;
    }

    public function makeColumnsRenderer($linesData, $dataOutput, $libs, $modeType)
    {
        $rowFields = $libs['row_fields'];
        $aggregations = $libs['data_fields'];

        if (empty($dataOutput)) return [[], []];
        if (!$this->getDataFields($linesData, $modeType)) return false;
        $columnsOfRowFields = $this->makeHeadColumn($rowFields);
        [$tableDataHeader, $columnsOfColumnFields] = $this->makeColumnsOfColumnFields($linesData, $dataOutput, $libs, $modeType);
        $columnsOfAgg = $this->makeColumnsOfAgg($aggregations);
        $tableColumns = array_merge($columnsOfRowFields, $columnsOfColumnFields, $columnsOfAgg);
        // dd($tableDataHeader, $columnsOfColumnFields);
        return [$tableDataHeader, $tableColumns];
    }
}
