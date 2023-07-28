<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\TraitLibPivotTableDataFields2;
use App\Http\Controllers\Workflow\LibPivotTables2;
use App\Utils\Support\PivotReport;
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
    public function changeValueData($data)
    {
        if ($this->modeType) {
            $bgColor = 'bg-gray-100';
            $results = [];
            $libs = LibPivotTables2::getFor($this->modeType);
            $rowFields = $libs['row_fields'];
            $fieldsUnShowTitle = ['staff_id'];
            $datesDoWork = [];
            foreach ($data as $values) {
                foreach ($values as $key => $value) {
                    foreach ($rowFields as $keyField => $attrs) {
                        $indexField = $keyField;
                        if (isset($attrs->column)) {
                            $indexField .= '_' . str_replace('.', '_', $attrs->column);
                        }
                        if ($key === $indexField) {
                            $values[$key] = (object) [
                                'value' => $value,
                                'cell_title' =>  in_array($keyField, $fieldsUnShowTitle) ? '' : 'ID: ' . (string)$values[$keyField],
                            ];
                        }
                    }
                    $date = $this->retrieveDateFromString($key);
                    if ($date) {
                        $date = str_replace('-', '/', $date);
                        $titleDate = DateTime::createFromFormat('d/m/y', $date)->format('d-m-Y');
                        $datesDoWork[$key] = $date;
                        $isSaturdaySunDay = PivotReport::isSaturdayOrSunday($date);
                        if ($isSaturdaySunDay) {
                            $values[$key] = (object) [
                                'value' => $value,
                                'cell_class' => $bgColor,
                                'cell_title' => 'Date: ' . $titleDate,
                            ];
                        } else {
                            $values[$key] = (object) [
                                'value' => $value,
                                'cell_title' => 'Date: ' . $titleDate
                            ];
                        }
                    }
                }
                $results[] = $values;
            }

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
        return $data;
    }
}
