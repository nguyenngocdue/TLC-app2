<?php

namespace App\View\Components\Renderer\Report;


use App\Http\Controllers\TraitLibPivotTableDataFields;
use App\Http\Controllers\Workflow\LibPivotTables;
use App\Utils\Support\Report;
use App\Utils\Support\ReportPivot;
use DateTime;
use Illuminate\View\Component;
use Illuminate\Support\Str;

trait  ColumnsPivotReport
{
    use TraitLibPivotTableDataFields;
    private function makeHeadColumn($bidingRowFields)
    {
        $columnsData = [];
        if (is_null($bidingRowFields)) return [];
        foreach ($bidingRowFields as $key => $value) {
            if (!$key) continue;
            if (count($value) and is_array($value)) {
                $dataIndex = $value['table_name'] . '_' . $value['attribute_name'];
                $title = ucwords(str_replace('_', ' ', substr($key, 0, strrpos($key, '_'))));
                $columnsData[] = [
                    'title' => $title,
                    'dataIndex' => $key . '_' . $dataIndex,
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

    protected function editRowHeaderColumnFields($fieldGroups, $valueIndexFields)
    {
        $array = [];
        // dd($fieldGroups);
        foreach ($fieldGroups as $key => $fields) {
            if ($key !== 'other') {
                foreach ($fields as  $field) {
                    $thirdUnderscore = ReportPivot::findPosition($field, '_', 3);
                    if ($thirdUnderscore) {
                        $date = Report::formatDateString(substr($field, 0, $thirdUnderscore - 1), 'd/m/y');
                        if ($date) $array[$field] = str_replace('/', '<br/>', $date);
                    }
                }
            } else {
                // dump($fields);

                foreach ($fields as $field) {
                    $checkFiled =  ReportPivot::isStringInItemsOfArray($valueIndexFields, $field);
                    if ($checkFiled) {
                        // $title = ucwords(substr($field, strpos($field, "_") + 1, strrpos($field, "_") - strpos($field, "_") - 1));
                        $title = ucwords(substr($field, strpos($field, "_") + 1, strrpos($field, "_") - strpos($field, "_") - 1));
                        // foreach ($valueIndexFields as $value) {
                        //     if (str_contains($field, $value)) {
                        //         $title = ucwords(str_replace('_', ' ', $value));
                        //     }
                        // }
                        if (str_contains($title, '[')) {
                            $title = self::removeNumberedString($title);
                        }
                        $array[$field] = $title;
                    }
                }
            }
        };
        // dd($array);
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


    private function sortDates($a)
    {
        $result = [];
        $lib = LibPivotTables::getFor($this->key);
        $columnFields = $lib['column_fields'] ?? [];
        $columnFields = ReportPivot::markDuplicates($columnFields);
        // dd($columnFields);
        $result = [];
        foreach ($columnFields as $key => $values) {
            foreach ($values as $value) {
                foreach ($a as $item) {
                    $thirdUnderscore = ReportPivot::findPosition($item, '_', 3);
                    if (substr($item, $thirdUnderscore, 4) === $value) {
                        $result[$value][] = $item;
                    }
                }
            }
        };
        // dd($result, $columnFields);
        foreach ($result as &$value) {
            usort($value, function ($date1, $date2) {
                $thirdUnderscore = ReportPivot::findPosition($date1, '_', 3);
                $dateTime1 = DateTime::createFromFormat('d_m_y', substr($date1, 0, $thirdUnderscore - 1));
                $dateTime2 = DateTime::createFromFormat('d_m_y', substr($date2, 0, $thirdUnderscore - 1));
                return $dateTime1 <=> $dateTime2;
            });
        }

        $otherItems = array_diff($a, array_merge(...array_values($result)));
        $result['other'] = $otherItems;
        return  $result;
    }

    private function makeColumnsOfColumnFields2($dataOutput, $columnFields)
    {
        $tableDataHeader = [];
        $columnsOfColumnFields = [];
        // if (count($columnFields) <= 2) {
        if (isset($dataOutput[0]['info_column_field'])) {
            $info = $dataOutput[0]['info_column_field'];
            $arrayInfo = array_merge(...array_merge(...array_values($info)));
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


    private function makeColumnsOfColumnFields($dataOutput)
    {
        // dd($dataOutput);
        $allColumns = [];
        [$rowFields,,,,,, $dataIndex,, $valueIndexFields, $columnFields] =  $this->getDataFields();
        if (!$this->getDataFields()) return false;

        foreach ($dataOutput as $value) $allColumns = array_unique(array_merge($allColumns, array_keys($value)));

        $lastItemDataSource = key(array_slice($this->dataSource[0] ?? [], -1));
        $endArray = Report::retrieveDataByIndex($allColumns, $lastItemDataSource, false, 'value');
        $diffFields = array_diff($endArray, $dataIndex);

        $fields = $this->sortDates($diffFields);

        $topTitleColumns = array_merge(...array_column($dataOutput, 'top_title_column'));
        $columnsOfColumnFields = [];
        if ($rowFields) {
            array_walk(
                $fields,
                function ($items, $key) use (&$columnsOfColumnFields, $topTitleColumns, $valueIndexFields, $columnFields) {
                    if ($key === 'other') {
                        $items = self::moveStringToBottom($items);
                        $groupItems = ReportPivot::groupItemsByString($items);
                        foreach ($items as $value) {
                            $checkFiled =  ReportPivot::isStringInItemsOfArray($valueIndexFields, $value);
                            if ($checkFiled) {
                                $firstWord = explode('_', $value)[0];
                                if (str_contains($value, '[')) {
                                    $title = ucwords(str_replace('_', ' ', explode(']', $value, 2)[0])) . ']';
                                }
                                $columnsOfColumnFields[] = [
                                    'title' => $topTitleColumns[$value] ?? $title ?? ucwords($columnFields[0]) ?? '',
                                    'dataIndex' => $value,
                                    'align' => is_numeric($value) ? 'left' : 'right',
                                    'width' => 50,
                                    // 'colspan' =>  round(count($groupItems[$firstWord]) / (count($valueIndexFields) ?? 1)),
                                    'colspan' =>  round(count($groupItems[$firstWord])),
                                ];
                            };
                        }
                    } else {
                        $items = self::moveStringToBottom($items);
                        $groupItems = ReportPivot::groupItemsByString($items, 'end');
                        foreach ($items as $value) {  
                            $thirdUnderscore = ReportPivot::findPosition($value, '_', 3);
                            $sixthUnderscore = ReportPivot::findPosition($value, '_', 4);
                            $endWord = substr($value, $thirdUnderscore);
                            if (!$thirdUnderscore) continue;
                            $columnsOfColumnFields[] = [
                                'title' => ucwords(str_replace('_', ' ', substr($value, $thirdUnderscore, $sixthUnderscore - $thirdUnderscore))),
                                'dataIndex' => $value,
                                'align' => is_numeric($value) ? 'left' : 'right',
                                'colspan' => count($groupItems[$endWord]),
                            ];
                        }
                    }
                }
            );
            $tableDataHeader = $this->editRowHeaderColumnFields($fields, $valueIndexFields);
            // dump($tableDataHeader);
        } else {
            [$tableDataHeader, $columnsOfColumnFields] = $this->makeColumnsOfColumnFields2($dataOutput, $columnFields);
        }
        // dd($tableDataHeader, $columnsOfColumnFields);
        return [$tableDataHeader, $columnsOfColumnFields];
    }

    private function makeColumnsOfAgg($dataAggregations)
    {
        $columnsOfAgg = [];
        if (!$dataAggregations) return [];
        foreach ($dataAggregations as $field => $fn) {
            $columnsOfAgg[] = [
                'dataIndex' => $fn . '_' . $field,
                'align' => 'right',
                'width' => 40,
            ];
        };
        return $columnsOfAgg;
    }

    public function makeColumnsRenderer($dataOutput)
    {
        [, $bidingRowFields,,,, $dataAggregations,,,,] = $this->getDataFields();
        if (!$this->getDataFields()) return false;
        $columnsOfRowFields = $this->makeHeadColumn($bidingRowFields);
        [$tableDataHeader, $columnsOfColumnFields] = $this->makeColumnsOfColumnFields($dataOutput);
        $columnsOfAgg = $this->makeColumnsOfAgg($dataAggregations);
        $tableColumns = array_merge($columnsOfRowFields, $columnsOfColumnFields, $columnsOfAgg);
        // dd($tableColumns);
        return [$tableDataHeader, $tableColumns];
    }
}
