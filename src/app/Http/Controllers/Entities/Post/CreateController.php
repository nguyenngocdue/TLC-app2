<?php

namespace App\Http\Controllers\Entities\Post;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Post;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'post';
    protected $data = Post::class;
    protected $action = "create";
}