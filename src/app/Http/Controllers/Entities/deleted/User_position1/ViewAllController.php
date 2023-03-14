<?php

namespace App\Http\Controllers\Entities\User_position1;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\User_position1;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'user_position1';
    protected $typeModel = User_position1::class;
    protected $permissionMiddleware = [
        'read' => 'read-user_position1s',
        'edit' => 'read-user_position1s|create-user_position1s|edit-user_position1s|edit-others-user_position1s',
        'delete' => 'read-user_position1s|create-user_position1s|edit-user_position1s|edit-others-user_position1s|delete-user_position1s|delete-others-user_position1s'
    ];
}