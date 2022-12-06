<?php

namespace App\Http\Controllers\Manage\Comment;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Comment;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'comment';
    protected $typeModel = Comment::class;
}
