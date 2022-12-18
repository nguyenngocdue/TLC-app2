<?php

namespace App\Http\Controllers\Entities\Comment;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Comment;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'comment';
    protected $data = Comment::class;
    protected $action = "create";
}