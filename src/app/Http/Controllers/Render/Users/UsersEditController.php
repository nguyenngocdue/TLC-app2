<?php

namespace App\Http\Controllers\Render\Users;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\User;

class UsersEditController extends CreateEditController
{
    protected $type = "user";
    protected $data = User::class;
    protected $action = "edit";
}
