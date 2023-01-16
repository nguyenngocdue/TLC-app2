<?php

namespace App\Http\Controllers\Entities\User;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\User;

class ManageController extends AbstractManageController
{
    protected $type = 'user';
    protected $typeModel = User::class;
}