<?php

namespace App\Http\Controllers\Entities\User_team;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\User_team;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'user_team';
    protected $typeModel = User_team::class;
}