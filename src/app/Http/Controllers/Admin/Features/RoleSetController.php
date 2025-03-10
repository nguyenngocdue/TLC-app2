<?php

namespace App\Http\Controllers\Admin\Features;

use App\Http\Controllers\Admin\AdminController;
use App\Models\Role_set;

class RoleSetController extends AdminController
{
    protected $type = 'role_sets';
    protected $model = Role_set::class;
    protected $validateCreate = ['name' => 'required|min:3|max:255|unique:role_sets'];
    protected $validateUpdate = [
        'name' => 'required|min:3|max:255',
        'guard_name' => 'required|min:3|max:255'
    ];
}
