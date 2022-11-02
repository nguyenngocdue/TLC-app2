<?php

namespace App\Http\Controllers\Render\Users;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\User;

class UsersViewAllController extends ViewAllController
{
    protected $type = 'user';
    protected $typeModel = User::class;
    protected $permissionMiddleware = [
        'read' => 'read-users',
        'edit' => 'read-users|create-users|edit-users|edit-others-users',
        'delete' => 'read-users|create-users|edit-users|edit-others-users|delete-users|delete-others-users'
    ];
}
