<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_control_value;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Qaqc_insp_control_value;

class StatusController extends AbstractStatusController
{
    protected $type = 'qaqc_insp_control_value';
    protected $typeModel = Qaqc_insp_control_value::class;
}