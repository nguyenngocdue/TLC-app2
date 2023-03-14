<?php

namespace App\Http\Controllers\Entities\Comment;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Comment;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'comment';
    protected $typeModel = Comment::class;
}