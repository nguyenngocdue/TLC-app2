<?php

namespace App\Http\Controllers\Render\Post;

use App\Http\Controllers\Render\ActionRenderController;

class PostActionRenderController extends ActionRenderController
{
    protected $type = 'post';
    protected $data = App\Models\Post::class;
}
