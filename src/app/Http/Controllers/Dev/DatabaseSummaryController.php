<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Utils\Support\DBTable;
use App\Utils\Support\Entities;
use Illuminate\Http\Request;

class DatabaseSummaryController extends Controller
{
    function getType()
    {
        return "dashboard";
    }

    function index(Request $request)
    {
        $entities = Entities::getAll();
        // dump($entities);
        $columns = [];
        $dataSource = [];
        foreach ($entities as $entity) {
            $tableName = $entity->getTable();
            if (str_starts_with($tableName, "view_")) continue;
            $tableColumns = DBTable::getColumnNames($tableName);
            $dataSource[$tableName]['tableName'] = $tableName;
            foreach ($tableColumns as $column) {
                $columns[$column] = isset($columns[$column]) ? $columns[$column] + 1 : 1;
                $dataSource[$tableName][$column] = 1;
            }
            // dump($columns);
        }
        $columns1 = [];
        $columns = array_filter($columns, fn ($c) => $c > 2);
        arsort($columns);
        foreach ($columns as $column => $count) {
            $column1 = [
                'dataIndex' => $column,
                'align' => 'center',
                'title' => $column . " ($count)",
            ];
            $columns1[] = $column1;
        }
        // $columns = array_map(fn ($c) => ['dataIndex' => $c, 'align' => 'center'], array_keys($columns));
        array_unshift($columns1, ['dataIndex' => 'tableName']);
        // dump($columns);
        // dump($dataSource);
        return view("dev.database-summary", [
            'columns' => $columns1,
            'dataSource' => $dataSource,
        ]);
    }
}
