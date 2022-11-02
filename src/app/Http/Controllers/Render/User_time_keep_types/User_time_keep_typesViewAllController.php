<?php

namespace App\Http\Controllers\Render\User_time_keep_types;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\User_time_keep_type;

class User_time_keep_typesViewAllController extends ViewAllController
{
    protected $type = 'user_time_keep_type';
    protected $typeModel = User_time_keep_type::class;
    protected $permissionMiddleware = [
        'read' => 'read-user_time_keep_types',
        'edit' => 'read-user_time_keep_types|create-user_time_keep_types|edit-user_time_keep_types|edit-others-user_time_keep_types',
        'delete' => 'read-user_time_keep_types|create-user_time_keep_types|edit-user_time_keep_types|edit-others-user_time_keep_types|delete-user_time_keep_types|delete-others-user_time_keep_types'
    ];
}
