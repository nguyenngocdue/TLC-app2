<?php

namespace App\Http\Controllers\Manage\Comment_category;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Comment_category;

class TablePropController extends ManageTablePropController
{
    protected $type = 'comment_category';
    protected $typeModel = Comment_category::class;
}
