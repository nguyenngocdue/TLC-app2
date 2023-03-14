<?php

namespace App\Http\Controllers\Entities\User_team;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\User_team;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'user_team';
    protected $data = User_team::class;
}