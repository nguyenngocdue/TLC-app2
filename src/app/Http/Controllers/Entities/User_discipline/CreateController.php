<?php

namespace App\Http\Controllers\Entities\User_discipline;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\User_discipline;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'user_discipline';
    protected $data = User_discipline::class;
    protected $action = "create";
}