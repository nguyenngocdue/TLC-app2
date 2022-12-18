<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_control_value;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Qaqc_insp_control_value;

class StatusController extends ManageStatusController
{
    protected $type = 'qaqc_insp_control_value';
    protected $typeModel = Qaqc_insp_control_value::class;
}