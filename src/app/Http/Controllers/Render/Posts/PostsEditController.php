<?php

namespace App\Http\Controllers\Render\Posts;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Render\EditController;
use App\Models\Post;

class PostsEditController extends EditController
{
    protected $type = "post";
    protected $data = Post::class;
}
