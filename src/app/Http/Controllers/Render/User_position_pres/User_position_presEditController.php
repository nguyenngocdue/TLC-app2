<?php

namespace App\Http\Controllers\Render\User_position_pres;

use App\Http\Controllers\Render\EditController;
use App\Models\User_position_pre;

class User_position_presEditController extends EditController
{
    protected $type = 'user_position_pre';
    protected $data = User_position_pre::class;
}