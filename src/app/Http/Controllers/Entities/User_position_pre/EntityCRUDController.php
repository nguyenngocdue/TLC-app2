<?php

namespace App\Http\Controllers\Entities\User_position_pre;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\User_position_pre;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'user_position_pre';
    protected $data = User_position_pre::class;
}