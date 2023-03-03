<?php

namespace App\Http\Controllers\Entities\User_team_ot;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\User_team_ot;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'user_team_ot';
    protected $data = User_team_ot::class;
}