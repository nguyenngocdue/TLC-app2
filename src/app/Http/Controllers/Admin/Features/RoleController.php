<?php

namespace App\Http\Controllers\Admin\Features;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Role;

class RoleController extends AdminController
{
    protected $type = 'roles';
    protected $model = Role::class;
    protected $validateCreate = ['name' => 'required|min:3|max:255|unique:roles'];
    protected $validateUpdate = [
        'name' => 'required|min:3|max:255',
        'guard_name' => 'required|min:3|max:255'
    ];
}
