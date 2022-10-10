<?php

namespace App\Http\Controllers\Render\Posts;

use App\Http\Controllers\Render\RenderController;
use App\Models\Post;

class PostsRenderController extends RenderController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
    protected $permissionMiddleware = [
        'read' => 'read_post|edit_post|edit_other_post|delete_post|delete_other_post',
        'edit' => 'edit_post|edit_other_post',
        'delete' => 'delete_post|delete_other_post'
    ];
}
