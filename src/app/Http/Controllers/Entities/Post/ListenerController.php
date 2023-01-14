<?php

namespace App\Http\Controllers\Entities\Post;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Post;

class ListenerController extends AbstractListenerController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}
