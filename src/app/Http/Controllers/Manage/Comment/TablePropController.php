<?php

namespace App\Http\Controllers\Manage\Comment;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Comment;

class TablePropController extends ManageTablePropController
{
    protected $type = 'comment';
    protected $typeModel = Comment::class;
}
