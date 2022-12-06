<?php

namespace App\Http\Controllers\Render\Comments;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Comment;

class CommentsCreateController extends CreateEditController
{
    protected $type = 'comment';
    protected $data = Comment::class;
    protected $action = "create";
}