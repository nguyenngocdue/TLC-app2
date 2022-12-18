<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_control_value;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Qaqc_insp_control_value;

class PropController extends ManagePropController
{
    protected $type = 'qaqc_insp_control_value';
    protected $typeModel = Qaqc_insp_control_value::class;
}
