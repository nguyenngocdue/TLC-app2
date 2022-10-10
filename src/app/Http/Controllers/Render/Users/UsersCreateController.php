<?php

namespace App\Http\Controllers\Render\Users;

use App\Http\Controllers\Render\EditController;
use App\Models\User;

class UsersCreateController extends EditController
{
    protected $type = "user";
    protected $data = User::class;
    protected $action = "create";
}
