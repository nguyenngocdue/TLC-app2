<?php

namespace App\View\Components\Renderer\Report;


use App\Http\Controllers\TraitLibPivotTableDataFields;
use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\Report;
use App\Utils\Support\PivotReport;
use DateTime;


trait  ColumnsPivotReport
{
    use TraitLibPivotTableDataFields;
    private function makeHeadColumn($bindingRowFields)
    {
        $columnsData = [];
        if (is_null($bindingRowFields)) return [];
        foreach ($bindingRowFields as $key => $value) {
            if (!$key) continue;
            if (count($value) && is_array($value)) {
                $dataIndex = $value['table_name'] ? $key . '_' . $value['table_name'] . '_' . $value['attribute_name'] : $key;
                $columnsData[] = [
                    'title' => $value['title_override'],
                    'dataIndex' => $dataIndex,
                    'width' => 250,
                ];
            } else {
                $columnsData[] = [
                    'dataIndex' => $key,
                    'width' => 250,
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

    protected function editRowHeaderColumnFields($fieldGroups, $columnFields, $topTitleColumns)
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
                    $checkFiled =  PivotReport::isStringInItemsOfArray($columnFields, $field);
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
        $lib = LibPivotTables::getFor($modeType);
        $columnFields = $lib['column_fields'] ?? [];
        $columnFields = PivotReport::markDuplicatesAndGroupKey($columnFields);
        $result = [];
        foreach ($columnFields as $key => $values) {
            foreach ($values as $value) {
                foreach ($a as $item) {
                    $thirdUnderscore = PivotReport::findPosition($item, '_', 3);
                    // $substr = substr($item, $thirdUnderscore,  4));
                    $substr = substr($item, $thirdUnderscore,  strlen($value));
                    if ($substr === $value) {
                        // dump($substr, $value, $item);
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

    private function findItemsByMapValueIndexField($data, $targetValue, $key = 'map_value_index_field')
    {
        $matchingItems = [];
        foreach ($data as $item) {
            if (!isset($item[$key])) continue;
            if ($item[$key] === $targetValue) {
                $matchingItems[] = $item;
            }
        }
        return $matchingItems;
    }



    private function makeColumnsOfColumnFields($linesData, $dataOutput, $allDataFields, $modeType)
    {
        [$rowFields, 
        $bindingRowFields, 
        $fieldOfFilters, 
        $propsColumnField, 
        $bindingColumnFields, 
        $dataAggregations, 
        $dataIndex, 
        $sortBy, 
        $valueIndexFields, 
        $columnFields, 
        $infoColumnFields, 
        $tableIndex,
        $dataFilters,] =  $allDataFields;
        $allColumns = [];
        // if (!$this->getDataFields()) return [false];

        foreach ($dataOutput as $value) $allColumns = array_unique(array_merge($allColumns, array_keys($value)));

        if (is_object($linesData)) $linesData = array_map(fn ($item) => (array)$item, $linesData->toArray());
        // dd($dataSource);

        $lastItemDataSource = key(array_slice($linesData[0] ?? [], -1));
        $endArray = Report::retrieveDataByIndex($allColumns, $lastItemDataSource, false, 'value');
        $diffFields = array_diff($endArray, $dataIndex);
        $fields = $this->sortDates($diffFields, $modeType);

        // dump($allColumns  , $lastItemDataSource, $endArray, $dataIndex);
        $topTitleColumns = array_merge(...array_column($dataOutput, 'top_title_column'));
        // dump($topTitleColumns, $propsColumnField);
        // dd($dataOutput, $fields);

        $columnsOfColumnFields = [];
        if ($rowFields) {
            array_walk(
                $fields,
                function ($items, $key) use (&$columnsOfColumnFields, $topTitleColumns, $propsColumnField) {
                    if ($key === 'other') {
                        $items = self::moveStringToBottom($items);
                        // $groupItems = PivotReport::groupItemsByString($items);
                        $groupItems = PivotReport::groupSimilarStrings($items);
                        foreach ($items as $value) {
                            $columnsNeedToRender = array_column($propsColumnField, 'field_index');
                            // dd($propsColumnField, $columnsNeedToRender, $items);
                            $checkField =  PivotReport::isStringInItemsOfArray($columnsNeedToRender, $value);
                            // dd($groupItems, $value, $items,$propsColumnField);
                            if ($checkField) {
                                $title = '';
                                $countRenderCol = 1;
                                foreach ($groupItems as $keyGroup => $group) {
                                    if (str_contains($value, $keyGroup) && in_array($value, $group) && isset($propsColumnField[$keyGroup])) {
                                        $info = $propsColumnField[$keyGroup];
                                        $title = $info['title_override'];
                                        $countRenderCol = count($group);
                                    }
                                }

                                // if ($topTitleColumns) {
                                //     $countItems = PivotReport::countItems($items);
                                //     $numDivideHeader = $countItems[$value];
                                // }
                                $columnsOfColumnFields[] = [
                                    // 'title' => $topTitleColumns[$value] ?? $title ?? ucwords($columnFields[0]) ?? '',
                                    'title' => $title,
                                    'dataIndex' => $value,
                                    'align' => is_numeric($value) ? 'left' : 'right',
                                    'width' => 50,
                                    'colspan' =>  $countRenderCol,
                                ];
                            };
                        }
                    } else {
                        $items = self::moveStringToBottom($items);
                        $groupItems = PivotReport::groupItemsByString($items, 'end');
                        // dd($groupItems);
                        foreach ($groupItems as $key => $dates) {
                            $attribute = $this->findItemsByMapValueIndexField($propsColumnField, $key);
                            // dd($items, $propsColumnField);
                            if (!$arr = reset($attribute)) continue;
                            $title = ($t = $arr['title_override']) ? $t : 'Empty Title';
                            foreach ($dates as $value) {
                                $columnsOfColumnFields[] = [
                                    'title' =>  $title,
                                    'dataIndex' => $value,
                                    'align' => is_numeric($value) ? 'left' : 'right',
                                    'colspan' => count($dates),
                                ];
                            }
                        }
                    }
                }
            );
            $tableDataHeader = $this->editRowHeaderColumnFields($fields, $columnFields, $topTitleColumns);
        } else {
            [$tableDataHeader, $columnsOfColumnFields] = $this->makeColumnsOfColumnFields2($dataOutput, $columnFields);
        }
        return [$tableDataHeader, $columnsOfColumnFields];
    }

    private function makeColumnsOfAgg($dataAggregations)
    {
        $columnsOfAgg = [];
        if (!$dataAggregations) return [];
        foreach ($dataAggregations as $field => $value) {
            $columnsOfAgg[] = [
                'dataIndex' => $value['title_override'],
                'align' => 'right',
                'width' => 40,
            ];
        };
        return $columnsOfAgg;
    }

    public function makeColumnsRenderer($linesData,$dataOutput, $allDataFields, $modeType)
    {
        // dd($dataOutput, $allDataFields);
        if(empty($dataOutput)) return [[],[]];
        [, $bindingRowFields,,,, $dataAggregations,,,,,,,,] = $allDataFields;
        if (!$this->getDataFields($linesData, $modeType)) return false;
        $columnsOfRowFields = $this->makeHeadColumn($bindingRowFields);
        [$tableDataHeader, $columnsOfColumnFields] = $this->makeColumnsOfColumnFields($linesData,$dataOutput, $allDataFields, $modeType);
        $columnsOfAgg = $this->makeColumnsOfAgg($dataAggregations);
        $tableColumns = array_merge($columnsOfRowFields, $columnsOfColumnFields, $columnsOfAgg);
        // dd($tableDataHeader, $columnsOfColumnFields);
        return [$tableDataHeader, $tableColumns];
    }
}
