<?php

namespace App\Http\Controllers\Render\User_position_pres;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\User_position_pre;

class User_position_presCreateController extends CreateEditController
{
    protected $type = 'user_position_pre';
    protected $data = User_position_pre::class;
    protected $action = "create";
}
