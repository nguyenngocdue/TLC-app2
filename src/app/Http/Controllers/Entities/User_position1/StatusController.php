<?php

namespace App\Http\Controllers\Entities\User_position1;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\User_position1;

class StatusController extends AbstractStatusController
{
    protected $type = 'user_position1';
    protected $typeModel = User_position1::class;
}