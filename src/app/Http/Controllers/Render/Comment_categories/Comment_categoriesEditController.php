<?php

namespace App\Http\Controllers\Render\Comment_categories;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Comment_category;

class Comment_categoriesEditController extends CreateEditController
{
    protected $type = 'comment_category';
    protected $data = Comment_category::class;
    protected $action = "edit";

}