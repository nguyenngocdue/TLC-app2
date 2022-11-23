<?php

namespace App\Http\Controllers\Manage\Medium;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Media;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'medium';
    protected $typeModel = Media::class;
}
