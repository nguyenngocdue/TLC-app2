<?php

namespace App\Http\Controllers\Render\User_categories;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\User_category;

class User_categoriesEditController extends CreateEditController
{
    protected $type = 'user_category';
    protected $data = User_category::class;
    protected $action = "edit";
}
