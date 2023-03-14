<?php

namespace App\Http\Controllers\Entities\User_type;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\User_type;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'user_type';
    protected $data = User_type::class;
}