<?php

namespace App\Http\Controllers\Entities\Pj_unit;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Pj_unit;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'pj_unit';
    protected $data = Pj_unit::class;
}