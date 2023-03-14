<?php

namespace App\Http\Controllers\Entities\User_discipline;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\User_discipline;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'user_discipline';
    protected $typeModel = User_discipline::class;
    protected $permissionMiddleware = [
        'read' => 'read-user_disciplines',
        'edit' => 'read-user_disciplines|create-user_disciplines|edit-user_disciplines|edit-others-user_disciplines',
        'delete' => 'read-user_disciplines|create-user_disciplines|edit-user_disciplines|edit-others-user_disciplines|delete-user_disciplines|delete-others-user_disciplines'
    ];
}