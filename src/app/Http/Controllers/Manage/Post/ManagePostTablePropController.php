<?php

namespace App\Http\Controllers\Manage\Post;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Post;

class ManagePostTablePropController extends ManageTablePropController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}
