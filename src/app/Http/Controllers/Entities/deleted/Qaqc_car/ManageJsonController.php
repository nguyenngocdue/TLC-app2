<?php

namespace App\Http\Controllers\Entities\Qaqc_car;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Qaqc_car;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'qaqc_car';
    protected $typeModel = Qaqc_car::class;
}