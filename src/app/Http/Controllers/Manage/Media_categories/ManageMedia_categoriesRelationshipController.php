<?php

namespace App\Http\Controllers\Manage\Media_categories;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Media_categories;

class ManageMedia_categoriesRelationshipController extends ManageRelationshipController
{
    protected $type = 'media_category';
    protected $typeModel = Media_categories::class;
}
