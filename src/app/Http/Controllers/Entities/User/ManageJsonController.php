<?php

namespace App\Http\Controllers\Entities\User;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\User;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'user';
    protected $typeModel = User::class;
}