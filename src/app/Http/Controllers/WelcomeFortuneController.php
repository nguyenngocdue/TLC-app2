<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Entities\ZZTraitManageJson\ManageProps;
use App\Http\Controllers\Workflow\LibApps;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WelcomeFortuneController extends Controller
{
    function __construct() {}

    function getType()
    {
        return "dashboard";
    }

    function echoSqlLine($var)
    {
        $result = "(";
        $array = [];
        foreach ($var as $key => $value) {
            if (is_null($value) || $value === "null") {
                $array[] = "null";
            } else {
                $array[] = "'$value'";
            }
        }
        $result .= implode(",", $array);
        $result .= ")";
        return $result;
    }

    function echoEntities()
    {
        $apps = array_values(LibApps::getAll());
        $columns = [
            "name",
            "package_tab",
            "package_rendered",
            "sub_package_rendered",
            "title",
            "nickname",
            "tutorial_link",
            "icon",
            "doc_id_format_column",
            "status",
            // "edit_renderer",
            // "show_renderer",
            // "package_rendered",
            // "sub_package_rendered",
        ];
        echo "INSERT INTO entities(
             `name`,
            `package_tab`,
            `package`,
            `sub_package`,
            `title`,
            `nickname`,
            `tutorial_link`,
            `icon`,
            `doc_id_columns`,
            `status`          
        ) VALUES \n";
        $array1 = [];
        foreach ($apps as $app) {
            $array0 = [];
            $str = "(";
            foreach ($columns as $column) {
                $value = $app[$column];
                if (is_null($value)) {
                    $array0[] = "null";
                } else {
                    if (is_string($value)) {
                        $array0[] = "'$value'";
                    } else {
                        $array0[] = $value;
                    }
                }
            }
            $str .= implode(",", $array0);
            $str .= ")";
            $array1[] = $str;
        }
        echo implode(",\n", $array1);
    }

    function echoEntityColumns()
    {
        $apps = array_keys(LibApps::getAll());
        echo "INSERT INTO entity_columns(
            `entity_id`,
            `name`,
            `label`,
            `column_type`,
            `column_length`,
            -- `nullable`,
            `unsigned`,
            `default_value`
        ) VALUES \n";
        foreach ($apps as $index => $app) {
            $entity_id = 1001 + $index;
            // dump($app);

            $sp = SuperProps::getFor($app);
            $props = array_values($sp['props']);
            $item = [];
            $lines = [];
            foreach ($props as $entityColumn) {
                $item['entity_id'] = $entity_id;
                $item['name'] = substr($entityColumn['name'], 1);
                $item['label'] = $entityColumn['label'];

                //Remove the words in the brackets
                $column_type_0 = preg_replace('/\(.*?\)/', '', $entityColumn['column_type']);
                //Remove the word "unsigned"
                $column_type_1 = str_replace("unsigned", "", $column_type_0);
                $item['column_type'] = trim($column_type_1);

                preg_match('/\((.*?)\)/', $entityColumn['column_type'], $matches);
                $item['column_length'] = $matches[1] ?? null;
                // $item['nullable'] = ??;
                $item['unsigned'] = str_contains($entityColumn['column_type'], "unsigned") ? 1 : 0;
                $item['default_value'] = $entityColumn['default-values']['default_value'] ?? "null";

                // dump($item);
                $lines[] = $this->echoSqlLine($item);
            }
            echo implode(",\n", $lines);
            break;
        }
    }

    public function index(Request $request)
    {
        // $this->echoEntities();
        $this->echoEntityColumns();

        return view("welcome-fortune", []);
    }
}
