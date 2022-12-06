<?php

namespace App\Http\Controllers\Manage\Comment_category;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Comment_category;

class StatusController extends ManageStatusController
{
    protected $type = 'comment_category';
    protected $typeModel = Comment_category::class;
}