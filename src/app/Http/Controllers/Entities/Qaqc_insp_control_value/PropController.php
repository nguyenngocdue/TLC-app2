<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_control_value;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Qaqc_insp_control_value;

class PropController extends AbstractPropController
{
    protected $type = 'qaqc_insp_control_value';
    protected $typeModel = Qaqc_insp_control_value::class;
}
