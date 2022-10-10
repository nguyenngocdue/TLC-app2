<?php

namespace App\Http\Controllers\Render\Users;

use App\Http\Controllers\Render\RenderController;
use App\Models\User;

class UsersRenderController extends RenderController
{
    protected $type = 'user';
    protected $typeModel = User::class;
    protected $permissionMiddleware = [
        'read' => 'read_user|edit_user|edit_other_user|delete_user|delete_other_user',
        'edit' => 'edit_user|edit_other_user',
        'delete' => 'delete_user|delete_other_user'
    ];
}
