<?php

namespace App\Http\Controllers\Entities\Qaqc_mir;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Qaqc_mir;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'qaqc_mir';
    protected $data = Qaqc_mir::class;
}