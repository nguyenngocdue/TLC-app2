<?php

namespace App\Http\Controllers\Entities\User_type;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\User_type;

class StatusController extends AbstractStatusController
{
    protected $type = 'user_type';
    protected $typeModel = User_type::class;
}