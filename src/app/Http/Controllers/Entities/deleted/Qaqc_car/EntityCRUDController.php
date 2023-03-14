<?php

namespace App\Http\Controllers\Entities\Qaqc_car;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Qaqc_car;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'qaqc_car';
    protected $data = Qaqc_car::class;
}