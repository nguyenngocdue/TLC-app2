<?php

namespace App\Http\Controllers\Render\Users;

use App\Http\Controllers\Render\RenderController;
use App\Models\User;

class UsersRenderController extends RenderController
{
    protected $type = 'user';
    protected $typeModel = User::class;
    protected $permissionMiddleware = [
        'read' => 'read-user|create-user|edit-user|edit-others-user|delete-user|delete-others-user',
        'edit' => 'edit-user|edit-others-user',
        'delete' => 'delete-user|delete-others-user'
    ];
}
