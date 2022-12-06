<?php

namespace App\Http\Controllers\Render\Comments;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Comment;

class CommentsEditController extends CreateEditController
{
    protected $type = 'comment';
    protected $data = Comment::class;
    protected $action = "edit";

}