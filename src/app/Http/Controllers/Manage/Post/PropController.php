<?php

namespace App\Http\Controllers\Manage\Post;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Post;

class PropController extends ManagePropController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}
