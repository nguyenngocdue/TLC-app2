<?php

namespace App\Http\Controllers\Render\User_position3s;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\User_position3;

class User_position3sEditController extends CreateEditController
{
    protected $type = 'user_position3';
    protected $data = User_position3::class;
}
