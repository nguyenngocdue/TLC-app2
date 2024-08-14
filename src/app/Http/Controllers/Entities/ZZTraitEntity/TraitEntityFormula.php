<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Models\User;
use App\Utils\Support\CurrentUser;
use App\Utils\Support\Json\SuperProps;
use App\View\Components\Formula\All_ClosedAt;
use App\View\Components\Formula\All_ConcatNameWith123;
use App\View\Components\Formula\All_DefinitionOfNew;
use App\View\Components\Formula\All_DocId;
use App\View\Components\Formula\All_SlugifyByName;
use App\View\Components\Formula\NCR_Report_Type;
use App\View\Components\Formula\TSO_GetAssignee1;
use App\View\Components\Formula\User_PositionRendered;
use App\View\Components\Formula\Wir_NameRendered;
use Illuminate\Support\Facades\Log;

trait TraitEntityFormula
{
    private function applyFormula($item, $action, $status = null)
    {
        $type = $this->type;
        $sp = SuperProps::getFor($type)['props'];
        $defaultValues = [];
        foreach ($sp as $propName => $prop) {
            if (isset($prop['default-values']['formula']) && $prop['default-values']['formula']) {
                $key = substr($propName, 1);
                $defaultValues[$key] = $prop['default-values'];
            }
        }
        // $defaultValues = DefaultValues::getAllOf($type);
        // dd($defaultValues);
        foreach ($defaultValues as $propName => $prop) {
            if ($prop['formula'] === '') continue;
            if (in_array($prop['formula'], ['All_OwnerId', 'All_DocId',]) && $action == 'update') continue;

            switch ($prop['formula']) {
                case "All_ConcatNameWith123":
                    $name = $item['name'] ?? "";
                    $value = (new All_ConcatNameWith123())($name);
                    break;
                case "All_SlugifyByName":
                    $id = $item['id'] ?? null;
                    $name = $item['name'] ?? "";
                    $name = $item['slug'] ?? $name;
                    $value = (new All_SlugifyByName())($name, $type, $id);
                    break;
                case "User_PositionRendered":
                    $id = $item['id'];
                    $user = User::find($id);
                    if (is_null($user)) {
                        $value = "";
                        break;
                    }
                    $position_pres = ($user->getPositionPrefix) ? $user->getPositionPrefix->name : "";
                    $position_1 = ($user->getPosition1) ? $user->getPosition1->name : "";
                    $position_2 = ($user->getPosition2) ? $user->getPosition2->name : "";
                    $position_3 = ($user->getPosition3) ? $user->getPosition3->name : "";
                    $value = (new User_PositionRendered())($position_pres, $position_1, $position_2, $position_3);
                    break;
                case "All_OwnerId":
                    //$item['owner_id']: passed from api parameter
                    $value = $item['owner_id'] ?? CurrentUser::get()->id;
                    break;
                case "All_DocId":
                    $value = (new All_DocId())($item, $type);
                    break;
                case "All_ClosedAt":
                    $value = (new All_ClosedAt())($status, $type);
                    break;
                case "All_DefinitionOfNew":
                    $value = (new All_DefinitionOfNew())($type);
                    break;
                    // case "Wir_NameRendered":
                    //     $value = (new Wir_NameRendered())($item);
                    //     break;
                case "TSO_GetAssignee1":
                    $assignee1 = $item['assignee_1'];
                    if ($assignee1) continue 2; // if timesheet has assignee_1 during update, do not update
                    $value = (new TSO_GetAssignee1())($type);
                    break;
                case "NCR_Report_Type":
                    $value = (new NCR_Report_Type())($item);
                    break;
                default:
                    $value = "";
                    break;
            }
            // dd($item);
            $item[$propName] = $value;
        }
        return $item;
    }
}
