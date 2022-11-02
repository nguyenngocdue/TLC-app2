<?php

namespace App\Http\Controllers\Render\Media_categories;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Media_category;

class Media_categoriesCreateController extends CreateEditController
{
    protected $type = 'media_category';
    protected $data = Media_category::class;
    protected $action = "create";
}