<?php

namespace App\Http\Controllers\Entities\User_position_pre;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\User_position_pre;

class EditController extends AbstractCreateEditController
{
    protected $type = 'user_position_pre';
    protected $data = User_position_pre::class;
    protected $action = "edit";

}