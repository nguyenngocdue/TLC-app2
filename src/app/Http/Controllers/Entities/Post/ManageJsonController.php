<?php

namespace App\Http\Controllers\Entities\Post;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Post;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}