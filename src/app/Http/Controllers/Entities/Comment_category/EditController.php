<?php

namespace App\Http\Controllers\Entities\Comment_category;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Comment_category;

class EditController extends AbstractCreateEditController
{
    protected $type = 'comment_category';
    protected $data = Comment_category::class;
    protected $action = "edit";

}