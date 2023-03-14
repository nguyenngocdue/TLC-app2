<?php

namespace App\Http\Controllers\Entities\Pj_shipment;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Pj_shipment;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'pj_shipment';
    protected $data = Pj_shipment::class;
}