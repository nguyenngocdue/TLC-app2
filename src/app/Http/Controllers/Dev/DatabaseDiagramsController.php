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

    function index(Request $request)
    {
        $tables = [];
        $tableNames = DBTable::getAll();
        // dump($tableNames);
        foreach ($tableNames as $tableNameObj) {
            $tableName = $tableNameObj->Tables_in_laravel;
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
        ]);
    }
}
