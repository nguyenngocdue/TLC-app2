<?php

namespace App\Http\Controllers\Entities\Pj_shipment;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Pj_shipment;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'pj_shipment';
    protected $typeModel = Pj_shipment::class;
}