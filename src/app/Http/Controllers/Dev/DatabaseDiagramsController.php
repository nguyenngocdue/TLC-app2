<?php

namespace App\Http\Controllers\Dev;

use App\Http\Controllers\Controller;
use App\Utils\Support\DBTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DatabaseDiagramsController extends Controller
{
    function getType()
    {
        return "dashboard";
    }

    function getSymbol($input)
    {
        switch ($input) {
            case 'PRI':
                return 'P';
            case "MUL":
                return "I";
            case "UNI":
                return "U";
            case "":
                return "";
            default:
                return "?";
        }
    }

    function getNodeDataArray($tables)
    {
        $nodeDataArray = [];
        $index = 0;
        $width = 10;
        foreach ($tables as $tableName => $table) {
            $x = 300 * ($index % $width);
            $y = 300 * floor($index / $width);
            $item['key'] = $tableName;
            $item['loc'] = "$x $y";
            $item['fields'] = [];
            // Log::info($index . " " . $x . " " . $y);
            foreach ($table as $field) {
                $item['fields'][] = [
                    'name' => $field['Field'],
                    'info' => $field['Type'],
                    'null' =>  $field['Null'] === "YES" ? "NULL" : "",
                    'key' => $this->getSymbol($field['Key']),
                    // 'extra' =>  $field['Extra'],
                    // 'default' =>  $field['Default'],
                ];
            }
            $nodeDataArray[] = $item;
            $index++;
            // if (sizeof($nodeDataArray) > 10) break;
        }
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
