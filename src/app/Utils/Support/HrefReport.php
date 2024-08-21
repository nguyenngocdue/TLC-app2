<?php

namespace App\Utils\Support;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HrefReport
{

    // private static function extractRouteParts($routeString)
    // {
    //     preg_match('/route\(([^,]+),\s*(\d+)\)/', $routeString, $matches);
    //     return [
    //         trim($matches[1], " \t\n\r\0\x0B'\""), $matches[2]
    //     ];
    // }

    public static function createDataHrefForRow($column, $dataLine)
    {
        $rowHrefFn = $column->row_href_fn;
        $allVariables = RegexReport::getAllVariables($rowHrefFn);
        [$variables, $fields]  = $allVariables;

        $result = array_combine(
            $variables,
            array_map(fn($field) => $dataLine->{$field} ?? null, $fields)
        );
        $href = str_replace($variables, array_values($result), $rowHrefFn);
        // [$entity, $action] = ["", ""];

        // if (str_contains($href, 'route(')) {
        //     [$routeName, $id] = static::extractRouteParts($href);
        //     [$entity, $action] = explode('.', $routeName);
        //     $href = Route::has($routeName) ? route($routeName, $id) : "#";
        // }
        return collect([
            'href' => $href,
            // 'entity' => $entity,
            // 'action' => $action,
        ]);
    }
}
