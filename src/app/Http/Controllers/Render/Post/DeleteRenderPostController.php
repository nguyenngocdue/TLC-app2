<?php

namespace App\Http\Controllers\Render\Post;

use App\Http\Controllers\Render\DeleteRenderController;

class DeleteRenderPostController extends DeleteRenderController
{
    protected $type = 'post';
    protected $data = App\Models\Post::class;
}
