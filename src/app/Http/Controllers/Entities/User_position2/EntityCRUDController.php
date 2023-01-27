<?php

namespace App\Http\Controllers\Entities\User_position2;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\User_position2;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'user_position2';
    protected $data = User_position2::class;
}