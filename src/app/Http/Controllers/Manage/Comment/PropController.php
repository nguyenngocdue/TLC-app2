<?php

namespace App\Http\Controllers\Manage\Comment;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Comment;

class PropController extends ManagePropController
{
    protected $type = 'comment';
    protected $typeModel = Comment::class;
}
