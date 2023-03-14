<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_value;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Qaqc_insp_value;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'qaqc_insp_value';
    protected $data = Qaqc_insp_value::class;
}