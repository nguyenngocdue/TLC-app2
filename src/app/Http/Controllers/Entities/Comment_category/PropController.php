<?php

namespace App\Http\Controllers\Entities\Comment_category;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Comment_category;

class PropController extends AbstractPropController
{
    protected $type = 'comment_category';
    protected $typeModel = Comment_category::class;
}
