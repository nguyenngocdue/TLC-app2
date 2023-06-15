<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\Report;

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
            $item = [];
            if (!in_array($route['name'], $jsonNames)) {
                $item['name'] = $route['name'];
                $item['row_color'] = 'green';
            } else {
                $item = $json[$route['name']];
            }
            $result[$route['name']] = $item;
        }
        return $result;
    }
}
