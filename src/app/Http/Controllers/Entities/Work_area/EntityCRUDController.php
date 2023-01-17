<?php

namespace App\Http\Controllers\Entities\Work_area;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Work_area;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'work_area';
    protected $data = Work_area::class;
}