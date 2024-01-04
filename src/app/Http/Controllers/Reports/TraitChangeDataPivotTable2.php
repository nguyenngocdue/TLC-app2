<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Workflow\LibPivotTables2;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use App\Utils\Support\StringPivotTable;
use DateTime;
use Exception;

trait TraitChangeDataPivotTable2
{
    use TraitLibPivotTableDataFields2;


    private function retrieveDateFromString($string)
    {
        $pos = PivotReport::findPosition($string, '_', 3);
        if ($pos > 0) {
            $dateString = str_replace('_', '-', substr($string, 0, $pos - 1));
            if (PivotReport::isValidDate($dateString)) return $dateString;
        }
        return '';
    }

    private function updateValueForRawData($linesData, $libs)
    {
        $rowFields = $libs['row_fields'];
        $columnInsert = $libs['insert_column_row_fields'];
        $tableName = $this->getTablesNamesFromLibs($libs);
        $tables = $this->getDataFromTables($tableName);
        foreach ($linesData as $k => &$lines) {
            foreach ($rowFields  as $key => $field) {
                if (isset($lines->$key) && isset($field->column)) {
                    [$tableName, $attr] = explode('.', $field->column, 2);
                    $id = $lines->$key;
                    $nameOfField = $tables[$tableName][$id]->{$attr};
                    $tooltip = $tables[$tableName][$id]->description ?? 'ID: ' . $id;
                    $lines->$key = (object)[
                        'value' => $nameOfField ?? $id,
                        'cell_title' => $tooltip,
                    ];
                }
            }
            // add link for values
            $newData = $lines;
            if(!empty($columnInsert)){
                foreach ($columnInsert as $keyColumn => $column) {
                    if(isset($column->href_from_field) && isset($column->route_name)){
                        $newData[$keyColumn] =(object) [
                            'value' =>  is_numeric($lines[$keyColumn]) ? '#'. $lines[$keyColumn] : $lines[$keyColumn],
                            'cell_href' =>  $column->route_name ||$column->href_from_field ? route($column->route_name, $lines[$column->href_from_field]) : "",
                            'cell_class' => 'text-blue-800',
                        ];
                    }
                }
            }
            $linesData[$k] = $newData;
        }
        return collect($linesData);
    }

    private function makeHrefForColumnFields($endRowField, $dataReduce, $columnFields, $linesData, $valDateInDB)
    {
        $href = null;
        foreach ($columnFields as $k1 => $valColFields) {
            if (!isset($valColFields->href_from_field)) return $href;
            $hrefToField = $valColFields->href_from_field;
            $f1 = $dataReduce[$endRowField];
            foreach ($linesData as $line) {
                if ($line->$endRowField === $f1 && $line->$k1 === $valDateInDB) {
                        $href = route($valColFields->route_name, $line->$hrefToField);
                        break;
                }
            }
        }
        return $href;
    }

    private function makeHrefForRowFields($indexRowField, $values)
    {
        if (isset($indexRowField->href_from_field) && isset($indexRowField->route_name)) {
            return route($indexRowField->route_name, $values[$indexRowField->href_from_field]);
        }
    }

    private function replaceKeysWithValues($url)
    {
        $params = explode('&', $url);
        foreach ($params as &$param) {
            $equalsPos = strpos($param, '=');
            if ($equalsPos !== false) {
                $param = substr($param, 0, $equalsPos + 1) . '{{' . substr($param, $equalsPos + 1) . '}}';
            }
        }
        $newUrl = implode('&', $params);
        return $newUrl;
    }

    public function changeValueData($data, $isRawData, $linesData, $params)
    {
        if ($isRawData) {
            $libs = LibPivotTables2::getFor($this->modeType);
            return $this->updateValueForRawData($data, $libs);
        }
        if ($this->modeType) {
            $bgColor = 'bg-gray-100';
            $results = [];
            $libs = LibPivotTables2::getFor($this->modeType);
            $rowFields = $libs['row_fields'];
            $dataFields = $libs['data_fields'];
            $fieldsUnShowTitle = ['staff_id'];
            $datesDoWork = [];

            $fieldsHaveHref = [];
            foreach ($dataFields as $items) {
                if (isset($items->href_str) && $items->href_str) {
                    $refRegex = $items->href_str;
                    $refRegex = $this->replaceKeysWithValues($refRegex);
                    $fieldsHaveHref[$items->aggregation . '_' . $items->value_index] = (object)[
                        'href_regex' => $refRegex,
                        'value_index' => $items->value_index,
                        'route_name' => $items->route_name
                    ];
                }
            };
            foreach ($data as $values) {
                foreach ($values as $key => $value) {
                    // Add link for DataField'columns
                    if(isset($fieldsHaveHref[$key])) {
                        $str = $fieldsHaveHref[$key] ->href_regex;
                        $routeName = $fieldsHaveHref[$key] ->route_name;
                        $fields = StringPivotTable::extractFields($str)[1];
                        $valueOfFields = array_intersect_key($values, array_flip($fields));
                        if (in_array('picker_date',array_keys($params)) && in_array('picker_date',array_values($fields))){
                            $valueOfFields = array_merge($valueOfFields,['picker_date' => $params['picker_date']]);
                        }
                        try {
                            $url = route($routeName). StringPivotTable::replaceValuesWithPlaceholders($valueOfFields, $str);
                        } catch (\Exception $e) {
                            dd('Oops, Please check the name of route for the "'.$this->modeType.'" key in Manage Pivot!');
                        }
                        $values[$key] = (object)[
                            'value' => $value,
                            'cell_class' => $url ? ' text-blue-800 ' : '',
                            'cell_href' => $url
                        ];
                    }

                    $endRowField = ''; // to get key of row_field for filtering cell_href
                    foreach ($rowFields as $keyField => $attrs) {
                        $indexField = $keyField;
                        $endRowField = $keyField;
                        if (isset($attrs->column)) {
                            $indexField .= '_' . str_replace('.', '_', $attrs->column);
                        }
                        if ($key === $indexField) {
                            try {
                                $href = $this->makeHrefForRowFields($rowFields[$keyField], $values);
                            } catch (\Exception $e) {
                                return response()->json($e);
                            }
                            $values[$key] = (object) [
                                'value' => $value,
                                'cell_title' =>  in_array($keyField, $fieldsUnShowTitle) ? '' : 'ID: ' . (string)$values[$keyField],
                                'cell_class' => $href ? ' text-blue-800 ' : '',
                                'cell_href' => $href
                            ];
                        }
                    }
                    $date = $this->retrieveDateFromString($key);
                    if ($date) {
                        $date = str_replace('-', '/', $date);
                        $columnFields = $libs['column_fields'];
                        $titleDate = DateTime::createFromFormat('d/m/y', $date)->format('d-m-Y');
                        $valDateInDB = DateTime::createFromFormat('d/m/y', $date)->format('Y-m-d');
                        $href = $this->makeHrefForColumnFields($endRowField, $values, $columnFields, $linesData, $valDateInDB);
                        $datesDoWork[$key] = $date;
                        $isSaturdaySunDay = PivotReport::isSaturdayOrSunday($date);
                        if ($isSaturdaySunDay) {
                            $values[$key] = (object) [
                                'value' => $value,
                                'cell_class' => $href ? $bgColor . ' text-blue-800 ' : $bgColor,
                                'cell_title' => 'Date: ' . $titleDate,
                                'cell_href' => $href,
                            ];
                        } else {
                            $values[$key] = (object) [
                                'value' => $value,
                                'cell_class' =>  $href ? 'text-blue-800' : '',
                                'cell_title' => 'Date: ' . $titleDate,
                                'cell_href' => $href,
                            ];
                        }
                    }
                }
                $results[] = $values;
            }
            // dd($results);
            // is Saturday Or Sunday
            foreach ($results as $key => &$values) {
                foreach ($datesDoWork as $k => $date) {
                    $isSaturdaySunDay = PivotReport::isSaturdayOrSunday($date);
                    if (!isset($values[$k]) && $isSaturdaySunDay) {
                        $titleDate = DateTime::createFromFormat('d/m/y', $date)->format('d-m-Y');
                        $values[$k] =  (object) [
                            'value' => '',
                            'cell_class' => $bgColor,
                            'cell_title' => 'Date: ' . $titleDate,
                        ];
                    }
                }
            }
            return collect($results);
        }
        // dd($data);
        return $data;
    }
}
