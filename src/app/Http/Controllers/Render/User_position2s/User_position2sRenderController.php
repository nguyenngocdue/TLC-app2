<?php

namespace App\Http\Controllers\Render\User_position2s;

use App\Http\Controllers\Render\RenderController;
use App\Models\User_position2;

class User_position2sRenderController extends RenderController
{
    protected $type = 'user_position2';
    protected $typeModel = User_position2::class;
    protected $permissionMiddleware = [
        'read' => 'read-user_position2s',
        'edit' => 'read-user_position2s|create-user_position2s|edit-user_position2s|edit-others-user_position2s',
        'delete' => 'read-user_position2s|create-user_position2s|edit-user_position2s|edit-others-user_position2s|delete-user_position2s|delete-others-user_position2s'
    ];
}