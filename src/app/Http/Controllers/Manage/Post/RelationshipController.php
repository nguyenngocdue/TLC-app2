<?php

namespace App\Http\Controllers\Manage\Post;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Post;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}
