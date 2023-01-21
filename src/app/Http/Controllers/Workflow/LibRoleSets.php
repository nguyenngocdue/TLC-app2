<?php

namespace App\Http\Controllers\Workflow;

use App\Models\RoleSet;
use Illuminate\Support\Str;

class LibRoleSets
{
    static function getAll()
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
