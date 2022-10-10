<?php

namespace App\Http\Controllers\Render\Users;

use App\Http\Controllers\Render\EditController;
use App\Models\User;

class UsersEditController extends EditController
{
    protected $type = "user";
    protected $action = "edit";
    protected $data = User::class;
}
