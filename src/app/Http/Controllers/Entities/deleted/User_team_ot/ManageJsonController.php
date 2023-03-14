<?php

namespace App\Http\Controllers\Entities\User_team_ot;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\User_team_ot;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'user_team_ot';
    protected $typeModel = User_team_ot::class;
}