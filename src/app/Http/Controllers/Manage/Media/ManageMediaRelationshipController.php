<?php

namespace App\Http\Controllers\Manage\Media;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Media;

class ManageMediaRelationshipController extends ManageRelationshipController
{
    protected $type = 'media';
    protected $typeModel = Media::class;
}
