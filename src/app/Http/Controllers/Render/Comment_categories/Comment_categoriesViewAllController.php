<?php

namespace App\Http\Controllers\Render\Comment_categories;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Comment_category;

class Comment_categoriesViewAllController extends ViewAllController
{
    protected $type = 'comment_category';
    protected $typeModel = Comment_category::class;
    protected $permissionMiddleware = [
        'read' => 'read-comment_categories',
        'edit' => 'read-comment_categories|create-comment_categories|edit-comment_categories|edit-others-comment_categories',
        'delete' => 'read-comment_categories|create-comment_categories|edit-comment_categories|edit-others-comment_categories|delete-comment_categories|delete-others-comment_categories'
    ];
}