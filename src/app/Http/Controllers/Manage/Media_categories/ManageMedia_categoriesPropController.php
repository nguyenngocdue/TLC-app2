<?php

namespace App\Http\Controllers\Manage\Media_categories;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Media_categories;

class ManageMedia_categoriesPropController extends ManagePropController
{
    protected $type = 'media_category';
    protected $typeModel = Media_categories::class;
}
