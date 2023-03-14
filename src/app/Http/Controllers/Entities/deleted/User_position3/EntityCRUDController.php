<?php

namespace App\Http\Controllers\Entities\User_position3;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\User_position3;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'user_position3';
    protected $data = User_position3::class;
}