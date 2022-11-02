<?php

namespace App\Http\Controllers\Render\User_position_pres;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\User_position_pre;

class User_position_presViewAllController extends ViewAllController
{
    protected $type = 'user_position_pre';
    protected $typeModel = User_position_pre::class;
    protected $permissionMiddleware = [
        'read' => 'read-user_position_pres',
        'edit' => 'read-user_position_pres|create-user_position_pres|edit-user_position_pres|edit-others-user_position_pres',
        'delete' => 'read-user_position_pres|create-user_position_pres|edit-user_position_pres|edit-others-user_position_pres|delete-user_position_pres|delete-others-user_position_pres'
    ];
}
