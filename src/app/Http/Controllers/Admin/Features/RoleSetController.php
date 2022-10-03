<?php

namespace App\Http\Controllers\Admin\Features;

use App\Http\Controllers\Admin\AdminController;
use App\Models\RoleSet;

class RoleSetController extends AdminController
{
    protected $type = 'role_sets';
    protected $model = RoleSet::class;
    protected $validateCreate = ['name' => 'required|min:3|max:255|unique:role_sets'];
    protected $validateUpdate = [
        'name' => 'required|min:3|max:255',
        'guard_name' => 'required|min:3|max:255'
    ];
}
