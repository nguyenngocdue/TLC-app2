<?php

namespace App\Http\Controllers\Entities\User_time_keep_type;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\User_time_keep_type;

class StatusController extends AbstractStatusController
{
    protected $type = 'user_time_keep_type';
    protected $typeModel = User_time_keep_type::class;
}