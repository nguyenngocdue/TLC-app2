<?php

namespace App\Http\Controllers\Entities\Post;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Post;

class PropController extends AbstractPropController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}
