<?php

namespace App\Http\Controllers\Entities\Control_type;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Control_type;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'control_type';
    protected $data = Control_type::class;
}