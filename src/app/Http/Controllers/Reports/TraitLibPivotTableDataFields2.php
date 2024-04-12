<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Workflow\LibPivotTables2;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait TraitLibPivotTableDataFields2
{

    public function getDataFromTables($tableIndex)
    {
        $dataTables = [];
        foreach (array_values($tableIndex) as $name) {
            if (!$name) continue;
            try {
                $array = DB::table($name)->select('id', 'name', 'description')->get()->toArray();
                $dataTables[$name]  = array_combine(array_column($array, 'id'), $array);;
            } catch (\Exception $e) {
                $array = DB::table($name)->select('id', 'name')->get()->toArray();
                $dataTables[$name]  = array_combine(array_column($array, 'id'), $array);
            }
        }
        return $dataTables;
    }
    public function getTablesNamesFromLibs($libs)
    {
        $colData = array_merge($libs['row_fields'], $libs['column_fields']);
        $columns = array_column($colData, 'column');
        $tableName = [];
        foreach ($columns as $col) {
            $str = substr($col, 0, strpos($col, '.'));
            $tableName[] = $str;
        }
        return $tableName;
    }

    public function getDataFields($modeType)
    {
        $libs = LibPivotTables2::getFor($modeType);
        return $libs;
    }

    public function getTableColumnsFromManagePivot($modeType)
    {
        $dataPivot = $this->getDataFields($modeType);
        $tableColumns = [];
        foreach ($dataPivot as $k => $items) {
            if ($k === 'row_fields') {
                foreach ($items as $k2 => $value) {
                    $colParser = isset($value->column) ? explode('.', $value->column) : $k2;
                    $dataIndex = Str::singular($colParser[0]) . '_' . $colParser[1];
                    $tableColumns[$k2] = [
                        'title' => isset($value->title) ? $value->title : $k2,
                        'dataIndex' => $dataIndex,
                        'align' => isset($value->align) ? $value->align : 'center',
                    ];
                    if (isset($value->footer)) $tableColumns[$k2] = array_merge($tableColumns[$k2], ['footer' => isset($value->footer) ? $value->footer : '']);
                }
            }
        }
        return $tableColumns;
    }
}
