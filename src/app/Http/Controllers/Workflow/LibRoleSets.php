<?php

namespace App\Http\Controllers\Workflow;

use App\Models\Role_set;
use Illuminate\Support\Str;

class LibRoleSets
{
    static function getAll()
    {
        $allRoleSets = Role_set::all()->pluck('name');
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
