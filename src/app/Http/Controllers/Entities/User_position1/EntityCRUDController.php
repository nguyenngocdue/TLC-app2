<?php

namespace App\Http\Controllers\Entities\User_position1;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\User_position1;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'user_position1';
    protected $data = User_position1::class;
}