<?php

namespace App\Http\Controllers\Entities\Post;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Post;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'post';
    protected $typeModel = Post::class;
}
