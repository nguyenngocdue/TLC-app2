<?php

namespace App\Http\Controllers\Entities\Comment;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Comment;

class StatusController extends AbstractStatusController
{
    protected $type = 'comment';
    protected $typeModel = Comment::class;
}