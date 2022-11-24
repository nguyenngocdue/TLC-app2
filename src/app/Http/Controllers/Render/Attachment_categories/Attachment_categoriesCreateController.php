<?php

namespace App\Http\Controllers\Render\Attachment_categories;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Attachment_category;

class Attachment_categoriesCreateController extends CreateEditController
{
    protected $type = 'attachment_category';
    protected $data = Attachment_category::class;
    protected $action = "create";
}
