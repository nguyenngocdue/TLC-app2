<?php

namespace App\Http\Controllers\Render\User_position3s;

use App\Http\Controllers\Render\EditController;
use App\Models\User_position3;

class User_position3sCreateController extends EditController
{
    protected $type = 'user_position3';
    protected $data = User_position3::class;
    protected $action = "create";
}