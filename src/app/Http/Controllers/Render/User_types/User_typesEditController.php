<?php

namespace App\Http\Controllers\Render\User_types;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\User_type;

class User_typesEditController extends CreateEditController
{
    protected $type = 'user_type';
    protected $data = User_type::class;
    protected $action = "edit";
}
