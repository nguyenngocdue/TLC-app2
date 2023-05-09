<?php

namespace App\Http\Controllers;

use App\Utils\Support\DBTable;
use App\Utils\Support\Entities;
use Illuminate\Http\Request;

class WelcomeFortuneController extends Controller
{
    public function getType()
    {
        return "dashboard";
    }

    public function index(Request $request)
    {
        $entities = Entities::getAll();
        // dump($entities);
        $columns = [];
        $dataSource = [];
        foreach ($entities as $entity) {
            // dump($entity->getTable());
            $tableName = $entity->getTable();
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
        return view("welcome-fortune", [
            'columns' => $columns1,
            'dataSource' => $dataSource,
        ]);
    }
}
