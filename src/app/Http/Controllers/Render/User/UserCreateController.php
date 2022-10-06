<?php

namespace App\Http\Controllers\Render\User;

use App\Http\Controllers\Render\EditController;
use App\Models\User;

class UserCreateController extends EditController
{
    protected $type = "user";
    protected $data = User::class;
    protected $action = "create";
}
