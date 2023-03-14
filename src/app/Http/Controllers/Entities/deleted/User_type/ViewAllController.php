<?php

namespace App\Http\Controllers\Entities\User_type;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\User_type;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'user_type';
    protected $typeModel = User_type::class;
    protected $permissionMiddleware = [
        'read' => 'read-user_types',
        'edit' => 'read-user_types|create-user_types|edit-user_types|edit-others-user_types',
        'delete' => 'read-user_types|create-user_types|edit-user_types|edit-others-user_types|delete-user_types|delete-others-user_types'
    ];
}