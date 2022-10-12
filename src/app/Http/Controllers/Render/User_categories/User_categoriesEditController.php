<?php

namespace App\Http\Controllers\Render\User_categories;

use App\Http\Controllers\Render\EditController;
use App\Models\User_category;

class User_categoriesEditController extends EditController
{
    protected $type = 'user_category';
    protected $data = User_category::class;
}