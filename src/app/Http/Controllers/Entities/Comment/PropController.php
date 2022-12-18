<?php

namespace App\Http\Controllers\Entities\Comment;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Comment;

class PropController extends AbstractPropController
{
    protected $type = 'comment';
    protected $typeModel = Comment::class;
}
