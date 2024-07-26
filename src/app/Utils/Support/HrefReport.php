<?php

namespace App\Utils\Support;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class HrefReport
{

    private static function extractRouteParts($routeString)
    {
        preg_match('/route\(([^,]+),\s*(\d+)\)/', $routeString, $matches);
        return [
            trim($matches[1], " \t\n\r\0\x0B'\""), $matches[2]
        ];
    }

    public static function createHrefForRow($column, $dataLine)
    {
        $rowHrefFn = $column->row_href_fn;
        $regexValues = RegexReport::pregLinkRowCell($rowHrefFn);
        [$variables, $fields]  = $regexValues;

        $result = array_combine(
            $variables,
            array_map(fn ($field) => $dataLine->{$field} ?? null, $fields)
        );
        $href = str_replace($variables, array_values($result), $rowHrefFn);

        if (str_contains($href, 'route(')) {
            [$routeName, $id] = static::extractRouteParts($href);
            $routeExits = Route::has($routeName);
            $href =  $routeExits ? route($routeName, $id) : "#";
        }
        return $href;
    }
}
