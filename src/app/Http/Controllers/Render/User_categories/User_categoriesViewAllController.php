<?php

namespace App\Http\Controllers\Render\User_categories;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\User_category;

class User_categoriesViewAllController extends ViewAllController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
    protected $permissionMiddleware = [
        'read' => 'read-user_categories',
        'edit' => 'read-user_categories|create-user_categories|edit-user_categories|edit-others-user_categories',
        'delete' => 'read-user_categories|create-user_categories|edit-user_categories|edit-others-user_categories|delete-user_categories|delete-others-user_categories'
    ];
}
