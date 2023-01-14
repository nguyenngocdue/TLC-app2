<?php

namespace App\Http\Controllers\Entities\Comment;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Comment;

class ListenerController extends AbstractListenerController
{
    protected $type = 'comment';
    protected $typeModel = Comment::class;
}
