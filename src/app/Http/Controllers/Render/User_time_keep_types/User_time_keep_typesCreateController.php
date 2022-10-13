<?php

namespace App\Http\Controllers\Render\User_time_keep_types;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\User_time_keep_type;

class User_time_keep_typesCreateController extends CreateEditController
{
    protected $type = 'user_time_keep_type';
    protected $data = User_time_keep_type::class;
    protected $action = "create";
}
