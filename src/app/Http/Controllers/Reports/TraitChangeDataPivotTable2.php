<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Workflow\LibPivotTables2;
use App\Utils\Support\PivotReport;
use App\Utils\Support\Report;
use DateTime;

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
        $tableName = $this->getTablesNamesFromLibs($libs);
        $tables = $this->getDataFromTables($tableName);

        foreach ($linesData as &$lines) {
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
        }
        return collect($linesData);
    }

    private function makeHrefForColumnFields($endRowField,$dataReduce,$columnFields,$linesData, $valDateInDB){
        $href = null;
        foreach ($columnFields as $k1 => $valColFields) {
            if(!isset($valColFields->href_from_field)) return $href;
            $hrefToField = $valColFields->href_from_field;      
                $f1 = $dataReduce[$endRowField];
                foreach ($linesData as $line){
                    if($line->$endRowField === $f1 && $line->$k1 === $valDateInDB) {
                        $href = route($valColFields->route_name, $line->$hrefToField);
                        break;
                    }
                }
        }
        return $href;
    }

    private function makeHrefForRowFields($indexRowField, $values){
        if(isset($indexRowField->href_from_field) && isset($indexRowField->route_name)){
            return route($indexRowField->route_name,$values[$indexRowField->href_from_field]);
        }
    }

    public function changeValueData($data, $isRawData, $linesData)
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
            $fieldsUnShowTitle = ['staff_id'];
            $datesDoWork = [];
            foreach ($data as $values) {
                foreach ($values as $key => $value) {
                    $endRowField = ''; // to get key of row_field for filtering cell_href
                    foreach ($rowFields as $keyField => $attrs) {
                        $indexField = $keyField;
                        $endRowField = $keyField;
                        if (isset($attrs->column)) {
                            $indexField .= '_' . str_replace('.', '_', $attrs->column);
                        }
                        if ($key === $indexField) {
                            $href = $this->makeHrefForRowFields($rowFields[$keyField], $values);    
                            $values[$key] = (object) [
                                'value' => $value,
                                'cell_title' =>  in_array($keyField, $fieldsUnShowTitle) ? '' : 'ID: ' . (string)$values[$keyField],
                                'cell_class' => $href ? ' text-blue-800 ':'',
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
                        $href = $this->makeHrefForColumnFields($endRowField,$values,$columnFields,$linesData,$valDateInDB);            
                        $datesDoWork[$key] = $date;
                        $isSaturdaySunDay = PivotReport::isSaturdayOrSunday($date);
                        if ($isSaturdaySunDay) {
                            $values[$key] = (object) [
                                'value' => $value,
                                'cell_class' => $href ? $bgColor.' text-blue-800 ':$bgColor,
                                'cell_title' => 'Date: ' . $titleDate,
                                'cell_href' => $href,
                            ];
                        } else {
                            $values[$key] = (object) [
                                'value' => $value,
                                'cell_class' =>  $href ? 'text-blue-800': '',
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
