<?php

namespace App\Http\Controllers\Entities\User_category;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\User_category;

class PropController extends AbstractPropController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
}
