<?php

namespace App\Http\Controllers\Render;

use App\Helpers\Helper;
use App\Utils\Support\Props;
use App\View\Components\Formula\All_ConcatNameWith123;
use App\View\Components\Formula\All_SlugifyByName;
use App\View\Components\Formula\User_PositionRendered;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                case "User_PositionRendered":
                    $ids_tableNames = ['user_position_pres' => $item['position_prefix'], 'user_position1s' => $item['position_1'],  'user_position2s' => $item['position_2'],  'user_position3s' => $item['position_3']];
                    $arrayValues = Helper::getValueByIdAndTableFromDB($ids_tableNames);
                    $value = (new User_PositionRendered())($arrayValues);
                    break;
                default:
                    break;
            }
            $item[$prop['column_name']] = $value;
        }
        return $item;
    }
}
