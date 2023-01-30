<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\User;
use App\Utils\Support\Json\DefaultValues;
use App\View\Components\Formula\All_ConcatNameWith123;
use App\View\Components\Formula\All_SlugifyByName;
use App\View\Components\Formula\User_PositionRendered;

trait TraitEntityFormula
{
    //TODO: remove $type
    private function apply_formula($item, $type)
    {
        $defaultValues = DefaultValues::getAllOf($type);
        $id = $item['id'];
        $name = $item['name'] ?? "";

        foreach ($defaultValues as $prop) {
            if ($prop['formula'] === '') continue;
            switch ($prop['formula']) {
                case "All_ConcatNameWith123":
                    $value = (new All_ConcatNameWith123())($name);
                    break;
                case "All_SlugifyByName":
                    $name = $item['slug'] ?? $name;
                    $value = (new All_SlugifyByName())($name, $type, $id);
                    break;
                case "User_PositionRendered":
                    $user = User::find($id);
                    if (is_null($user)) {
                        $value = "";
                        break;
                    }
                    $position_pres = ($user->positionPres) ? $user->positionPres->name : "";
                    $position_1 = ($user->position1) ? $user->position1->name : "";
                    $position_2 = ($user->position2) ? $user->position2->name : "";
                    $position_3 = ($user->position3) ? $user->position3->name : "";
                    $value = (new User_PositionRendered())($position_pres, $position_1, $position_2, $position_3);
                    break;
                default:
                    break;
            }
            $item[$prop['column_name']] = $value;
        }
        return $item;
    }
}
