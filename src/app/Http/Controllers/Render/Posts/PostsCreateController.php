<?php

namespace App\Http\Controllers\Render\Posts;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Post;

class PostsCreateController extends CreateEditController
{
    protected $type = "post";
    protected $data = Post::class;
    protected $action = "create";
}
