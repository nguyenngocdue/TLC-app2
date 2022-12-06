<?php

namespace App\Http\Controllers\Manage\Comment_category;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Comment_category;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'comment_category';
    protected $typeModel = Comment_category::class;
}
