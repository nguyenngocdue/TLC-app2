<?php

namespace App\Http\Controllers\Manage\Post;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Post;

class StatusController extends ManageStatusController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}
