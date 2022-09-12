<?php

namespace App\Http\Controllers\Render\Post;

use App\Http\Controllers\Render\ActionRenderController;
use App\Models\Post;

class PostActionRenderController extends ActionRenderController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}
