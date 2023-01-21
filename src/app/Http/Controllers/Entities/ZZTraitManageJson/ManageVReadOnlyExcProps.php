<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Models\RoleSet;
use App\Utils\Support\Json\ReadOnlyExcProps;
use Illuminate\Support\Str;

class ManageVReadOnlyExcProps extends ManageV_Parent
{
    protected $routeKey = "_rol-exc";
    protected $jsonGetSet = ReadOnlyExcProps::class;

    protected function getColumnSource()
    {
        $allRoleSets = RoleSet::all()->pluck('name');
        $result = [];
        foreach ($allRoleSets as $roleSet) {
            $result[$roleSet] = [
                'name' => $roleSet,
                'title' => Str::appTitle($roleSet),
            ];
        }
        return  $result;
    }
}
