<?php

namespace App\Http\Controllers\Render\User;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Render\EditController;
use App\Models\User;

class UserEditController extends EditController
{
    protected $type = "user";
    protected $data = User::class;
}
