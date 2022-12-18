<?php

namespace App\Http\Controllers\Entities\User;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\User;

class StatusController extends AbstractStatusController
{
    protected $type = 'user';
    protected $typeModel = User::class;
}