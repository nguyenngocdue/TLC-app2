<?php

namespace App\Http\Controllers\Entities\User_position2;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\User_position2;

class EditController extends AbstractCreateEditController
{
    protected $type = 'user_position2';
    protected $data = User_position2::class;
    protected $action = "edit";

}