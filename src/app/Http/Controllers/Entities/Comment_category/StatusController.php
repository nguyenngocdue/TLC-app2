<?php

namespace App\Http\Controllers\Entities\Comment_category;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Comment_category;

class StatusController extends AbstractStatusController
{
    protected $type = 'comment_category';
    protected $typeModel = Comment_category::class;
}