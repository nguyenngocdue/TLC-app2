<?php

namespace App\Http\Controllers\Render\Post;

use App\Http\Controllers\Render\RenderController;
use App\Models\Post;

class PostRenderController extends RenderController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}
