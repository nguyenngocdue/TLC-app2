<?php

namespace App\Http\Controllers\Render\User_position2s;

use App\Http\Controllers\Render\EditController;
use App\Models\User_position2;

class User_position2sEditController extends EditController
{
    protected $type = 'user_position2';
    protected $data = User_position2::class;
}