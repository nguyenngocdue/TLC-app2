<?php

namespace App\Http\Controllers\Entities\Post;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Post;

class ManageController extends AbstractManageController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}