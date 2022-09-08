<?php

namespace App\Http\Controllers\Render\Post;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Render\EditController;
use App\Models\Post;

class PostEditController extends EditController
{
    protected $type = "post";
    protected $data = Post::class;
}
