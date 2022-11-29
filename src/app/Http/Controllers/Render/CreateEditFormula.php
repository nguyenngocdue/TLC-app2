<?php

namespace App\Http\Controllers\Render;

use App\Utils\Support\Props;
use App\View\Components\Formula\All_ConcatNameWith123;
use App\View\Components\Formula\All_SlugifyByName;
use Illuminate\Http\Request;

trait CreateEditFormula
{
    private function apply_formula($item, $type)
    {
        $props = Props::getAllOf($type);

        foreach ($props as $prop) {
            if ($prop['formula'] === '') continue;
            switch ($prop['formula']) {
                case "All_ConcatNameWith123":
                    $value = (new All_ConcatNameWith123())($item['name']);
                    break;
                case "All_SlugifyByName":
                    $name = $item['slug'] ?? $item['name'];
                    $value = (new All_SlugifyByName())($name, $type, $item['id']);
                    break;
                default:
                    break;
            }
            $item[$prop['column_name']] = $value;
        }
        return $item;
    }
}
