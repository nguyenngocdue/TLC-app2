<?php

namespace App\Http\Controllers\Manage\Media_category;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Media_category;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'media_category';
    protected $typeModel = Media_category::class;
}
