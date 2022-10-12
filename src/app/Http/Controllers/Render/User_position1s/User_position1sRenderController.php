<?php

namespace App\Http\Controllers\Render\User_position1s;

use App\Http\Controllers\Render\RenderController;
use App\Models\User_position1;

class User_position1sRenderController extends RenderController
{
    protected $type = 'user_position1';
    protected $typeModel = User_position1::class;
    protected $permissionMiddleware = [
        'read' => 'read-user_position1s',
        'edit' => 'read-user_position1s|create-user_position1s|edit-user_position1s|edit-others-user_position1s',
        'delete' => 'read-user_position1s|create-user_position1s|edit-user_position1s|edit-others-user_position1s|delete-user_position1s|delete-others-user_position1s'
    ];
}