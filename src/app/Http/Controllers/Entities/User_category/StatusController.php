<?php

namespace App\Http\Controllers\Entities\User_category;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\User_category;

class StatusController extends AbstractStatusController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
}