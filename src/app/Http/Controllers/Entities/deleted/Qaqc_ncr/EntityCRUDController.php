<?php

namespace App\Http\Controllers\Entities\Qaqc_ncr;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Qaqc_ncr;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'qaqc_ncr';
    protected $data = Qaqc_ncr::class;
}