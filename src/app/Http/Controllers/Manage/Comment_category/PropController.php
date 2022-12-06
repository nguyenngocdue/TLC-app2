<?php

namespace App\Http\Controllers\Manage\Comment_category;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Comment_category;

class PropController extends ManagePropController
{
    protected $type = 'comment_category';
    protected $typeModel = Comment_category::class;
}
