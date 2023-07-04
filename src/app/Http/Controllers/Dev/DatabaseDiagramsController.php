<?php

namespace App\Http\Controllers\Dev;

use App\BigThink\Options;
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
            $option = Options::get("diagram.node.$tableName", (object)["loc" => "$x $y"], true);
            // dump($option);
            $item['key'] = $tableName;
            $item['loc'] = $option->loc;
            $item['fields'] = [];
            // Log::info($index . " " . $x . " " . $y);
            foreach ($table['columns'] as $field) {
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
            // if (sizeof($nodeDataArray) > 20) break;
        }
        return $nodeDataArray;
    }

    function getLinkDataArray($tables)
    {
        // $ignoredTo = [
        //     ['to' => 'users', 'toPort' => 'id'],
        // ];
        $result = [];
        foreach ($tables as $table) {
            // dump($table['relationships']);
            foreach ($table['relationships'] as $rel) {
                if ($rel["REFERENCED_TABLE_NAME"] == 'users' && $rel["REFERENCED_COLUMN_NAME"] == 'id') continue;
                $result[] = [
                    "from" => $rel["TABLE_NAME"],
                    "fromPort" => $rel["COLUMN_NAME"],
                    "to" => $rel["REFERENCED_TABLE_NAME"],
                    "toPort" => $rel["REFERENCED_COLUMN_NAME"],
                ];
            }
        }
        return $result;
    }

    function index(Request $request)
    {
        $tables = [];
        $tableNames = DBTable::getAll();
        foreach ($tableNames as $tableName) {
            $tables[$tableName]['columns'] = DBTable::getAllColumns($tableName, true);
            $tables[$tableName]['relationships'] = DBTable::getRelationships($tableName, true);
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
            'linkDataArray' => $this->getLinkDataArray($tables),
        ]);
    }
}
