<?php

namespace App\Http\Controllers\Entities\Comment;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Comment;

class ManageController extends AbstractManageController
{
    protected $type = 'comment';
    protected $typeModel = Comment::class;
}