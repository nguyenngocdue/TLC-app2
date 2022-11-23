<?php

namespace App\Http\Controllers\Manage\Media_category;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Media_category;

class TablePropController extends ManageTablePropController
{
    protected $type = 'media_category';
    protected $typeModel = Media_category::class;
}
