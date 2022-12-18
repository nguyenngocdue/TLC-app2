<?php

namespace App\Http\Controllers\Entities\User;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\User;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'user';
    protected $data = User::class;
    protected $action = "create";
}