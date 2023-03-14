<?php

namespace App\Http\Controllers\Entities\Comment;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Comment;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'comment';
    protected $data = Comment::class;
}