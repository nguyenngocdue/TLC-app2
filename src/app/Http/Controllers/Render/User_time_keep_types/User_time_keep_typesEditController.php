<?php

namespace App\Http\Controllers\Render\User_time_keep_types;

use App\Http\Controllers\Render\EditController;
use App\Models\User_time_keep_type;

class User_time_keep_typesEditController extends EditController
{
    protected $type = 'user_time_keep_type';
    protected $data = User_time_keep_type::class;
}