<?php

namespace App\Http\Controllers\Entities\Comment;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Comment;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'comment';
    protected $typeModel = Comment::class;
    protected $permissionMiddleware = [
        'read' => 'read-comments',
        'edit' => 'read-comments|create-comments|edit-comments|edit-others-comments',
        'delete' => 'read-comments|create-comments|edit-comments|edit-others-comments|delete-comments|delete-others-comments'
    ];
}