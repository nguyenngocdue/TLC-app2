<?php

namespace App\Http\Controllers\Entities\Pj_staircase;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Pj_staircase;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'pj_staircase';
    protected $data = Pj_staircase::class;
}