<?php

namespace App\Http\Controllers\Entities\User_category;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\User_category;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
    protected $permissionMiddleware = [
        'read' => 'read-user_categories',
        'edit' => 'read-user_categories|create-user_categories|edit-user_categories|edit-others-user_categories',
        'delete' => 'read-user_categories|create-user_categories|edit-user_categories|edit-others-user_categories|delete-user_categories|delete-others-user_categories'
    ];
}