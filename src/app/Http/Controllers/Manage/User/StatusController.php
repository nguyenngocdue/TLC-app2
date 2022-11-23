<?php

namespace App\Http\Controllers\Manage\User;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\User;

class StatusController extends ManageStatusController
{
    protected $type = 'user';
    protected $typeModel = User::class;
}