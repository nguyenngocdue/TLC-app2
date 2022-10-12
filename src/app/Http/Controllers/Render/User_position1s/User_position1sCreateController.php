<?php

namespace App\Http\Controllers\Render\User_position1s;

use App\Http\Controllers\Render\EditController;
use App\Models\User_position1;

class User_position1sCreateController extends EditController
{
    protected $type = 'user_position1';
    protected $data = User_position1::class;
    protected $action = "create";
}