<?php

namespace App\Http\Controllers\Entities\User_category;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\User_category;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'user_category';
    protected $data = User_category::class;
    protected $action = "create";
}