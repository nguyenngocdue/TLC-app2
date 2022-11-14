<?php

namespace App\Http\Controllers\Manage\Media_categories;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Media_categories;

class ManageMedia_categoriesTablePropController extends ManageTablePropController
{
    protected $type = 'media_category';
    protected $typeModel = Media_categories::class;
}
