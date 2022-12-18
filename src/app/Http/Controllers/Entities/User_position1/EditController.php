<?php

namespace App\Http\Controllers\Entities\User_position1;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\User_position1;

class EditController extends AbstractCreateEditController
{
    protected $type = 'user_position1';
    protected $data = User_position1::class;
    protected $action = "edit";

}