<?php

namespace App\Http\Controllers\Manage\Post;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Post;

class ManagePostStatusController extends ManageStatusController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}
