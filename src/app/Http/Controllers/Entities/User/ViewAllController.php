<?php

namespace App\Http\Controllers\Entities\User;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\User;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'user';
    protected $typeModel = User::class;
    protected $permissionMiddleware = [
        'read' => 'read-users',
        'edit' => 'read-users|create-users|edit-users|edit-others-users',
        'delete' => 'read-users|create-users|edit-users|edit-others-users|delete-users|delete-others-users'
    ];
}