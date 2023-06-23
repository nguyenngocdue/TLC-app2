<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Utils\Support\DBTable;
use Illuminate\Http\Request;

class DatabaseDiagramsController extends Controller
{
    function getType()
    {
        return "dashboard";
    }

    function getNodeDataArray($tables)
    {
        $nodeDataArray = [];
        foreach ($tables as $tableName => $table) {
            $item['key'] = $tableName;
            $item['loc'] = "";
            foreach ($table as $field) {
                $item['field'][] = [
                    'name' => $field['Field'],
                ];
            }
            $nodeDataArray[] = $item;
            if (sizeof($nodeDataArray) > 10) break;
        }
        // $nodeDataArray = [
        //     [
        //         'key' => "Record2",
        //         'fields' => [
        //             ['name' => "fieldA", 'info' => "", 'color' => "#FFB900", 'figure' => "Diamond"],
        //             ['name' => "fieldB", 'info' => "", 'color' => "#F25022", 'figure' => "Rectangle"],
        //             ['name' => "fieldC", 'info' => "", 'color' => "#7FBA00", 'figure' => "Diamond"],
        //             ['name' => "fieldD", 'info' => "fourth", 'color' => "#00BCF2", 'figure' => "Rectangle"],
        //         ],
        //         'loc' => "280 100"
        //     ]
        // ];
        // dd($nodeDataArray);
        return $nodeDataArray;
    }

    function index(Request $request)
    {
        $tables = [];
        $tableNames = DBTable::getAll();
        foreach ($tableNames as $tableName) {
            $tables[$tableName] = DBTable::getAllColumns($tableName, true);
        }
        // dump($tables);
        $columns = [
            ['dataIndex' => 'Field'],
            ['dataIndex' => 'Type'],
            ['dataIndex' => 'Null'],
            ['dataIndex' => 'Key'],
            ['dataIndex' => 'Default'],
            ['dataIndex' => 'Extra'],
        ];

        return view("dev.database-diagrams", [
            'columns' => $columns,
            'tables' => $tables,
            'nodeDataArray' => $this->getNodeDataArray($tables),
        ]);
    }
}
