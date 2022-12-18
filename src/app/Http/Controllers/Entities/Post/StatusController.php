<?php

namespace App\Http\Controllers\Entities\Post;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Post;

class StatusController extends AbstractStatusController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}