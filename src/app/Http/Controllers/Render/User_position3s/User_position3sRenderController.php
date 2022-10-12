<?php

namespace App\Http\Controllers\Render\User_position3s;

use App\Http\Controllers\Render\RenderController;
use App\Models\User_position3;

class User_position3sRenderController extends RenderController
{
    protected $type = 'user_position3';
    protected $typeModel = User_position3::class;
    protected $permissionMiddleware = [
        'read' => 'read-user_position3s',
        'edit' => 'read-user_position3s|create-user_position3s|edit-user_position3s|edit-others-user_position3s',
        'delete' => 'read-user_position3s|create-user_position3s|edit-user_position3s|edit-others-user_position3s|delete-user_position3s|delete-others-user_position3s'
    ];
}