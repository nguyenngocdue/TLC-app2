<?php

namespace App\Http\Controllers\Render\Posts;

use App\Http\Controllers\Render\RenderController;
use App\Models\Post;

class PostsRenderController extends RenderController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
    protected $permissionMiddleware = [
        'read' => 'read-post',
        'edit' => 'read-post|create-post|edit-post|edit-others-post',
        'delete' => 'read-post|create-post|edit-post|edit-others-post|delete-post|delete-others-post'
    ];
}
