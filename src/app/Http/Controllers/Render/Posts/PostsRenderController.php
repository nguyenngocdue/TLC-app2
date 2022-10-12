<?php

namespace App\Http\Controllers\Render\Posts;

use App\Http\Controllers\Render\RenderController;
use App\Models\Post;

class PostsRenderController extends RenderController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
    protected $permissionMiddleware = [
        'read' => 'read-posts',
        'edit' => 'read-posts|create-posts|edit-posts|edit-others-posts',
        'delete' => 'read-posts|create-posts|edit-posts|edit-others-posts|delete-posts|delete-others-posts'
    ];
}
