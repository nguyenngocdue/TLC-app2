<?php

namespace App\Http\Controllers\Entities\Comment;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Comment;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'comment';
    protected $typeModel = Comment::class;
}
