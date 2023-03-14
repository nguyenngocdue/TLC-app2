<?php

namespace App\Http\Controllers\Entities\Post;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Post;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'post';
    protected $data = Post::class;
}