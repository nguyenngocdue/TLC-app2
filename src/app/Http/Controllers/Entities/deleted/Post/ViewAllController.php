<?php

namespace App\Http\Controllers\Entities\Post;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Post;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
    protected $permissionMiddleware = [
        'read' => 'read-posts',
        'edit' => 'read-posts|create-posts|edit-posts|edit-others-posts',
        'delete' => 'read-posts|create-posts|edit-posts|edit-others-posts|delete-posts|delete-others-posts'
    ];
}