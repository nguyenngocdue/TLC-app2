<?php

namespace App\Http\Controllers\Entities\Pj_pod;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Pj_pod;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'pj_pod';
    protected $data = Pj_pod::class;
}