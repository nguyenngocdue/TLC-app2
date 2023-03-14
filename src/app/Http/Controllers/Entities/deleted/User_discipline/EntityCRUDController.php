<?php

namespace App\Http\Controllers\Entities\User_discipline;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\User_discipline;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'user_discipline';
    protected $data = User_discipline::class;
}