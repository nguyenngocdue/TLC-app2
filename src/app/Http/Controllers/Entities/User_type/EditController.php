<?php

namespace App\Http\Controllers\Entities\User_type;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\User_type;

class EditController extends AbstractCreateEditController
{
    protected $type = 'user_type';
    protected $data = User_type::class;
    protected $action = "edit";

}