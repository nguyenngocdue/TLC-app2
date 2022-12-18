<?php

namespace App\Http\Controllers\Entities\Comment_category;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Comment_category;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'comment_category';
    protected $typeModel = Comment_category::class;
}
