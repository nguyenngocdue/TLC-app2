<?php

namespace App\Http\Controllers\Entities\User_position3;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\User_position3;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'user_position3';
    protected $data = User_position3::class;
    protected $action = "create";
}