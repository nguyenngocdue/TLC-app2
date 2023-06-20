<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\Report;
use Illuminate\Support\Str;

class LibReports extends AbstractLib
{
    protected static $key = "reports";

    public static function getAll()
    {
        $json = parent::getAll();
        $jsonNames = array_map(fn ($i) => $i['name'], $json);
        $routes = Report::getAllRoutes();
        $result = [];
        foreach ($routes as $route) {
            $tableName = Str::plural($route['singular']);
            $item = [];
            if (!in_array($route['name'], $jsonNames)) {
                $item['name'] = $route['name'];
                $item['row_color'] = 'green';
            } else {
                $item = $json[$route['name']];
            }
            $item['sub_package'] = $tableName;
            $result[$route['name']] = $item;
        }
        return $result;
    }
}
