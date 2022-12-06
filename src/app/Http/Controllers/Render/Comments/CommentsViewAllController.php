<?php

namespace App\Http\Controllers\Render\Comments;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Comment;

class CommentsViewAllController extends ViewAllController
{
    protected $type = 'comment';
    protected $typeModel = Comment::class;
    protected $permissionMiddleware = [
        'read' => 'read-comments',
        'edit' => 'read-comments|create-comments|edit-comments|edit-others-comments',
        'delete' => 'read-comments|create-comments|edit-comments|edit-others-comments|delete-comments|delete-others-comments'
    ];
}