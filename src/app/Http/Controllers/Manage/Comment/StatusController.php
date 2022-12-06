<?php

namespace App\Http\Controllers\Manage\Comment;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Comment;

class StatusController extends ManageStatusController
{
    protected $type = 'comment';
    protected $typeModel = Comment::class;
}