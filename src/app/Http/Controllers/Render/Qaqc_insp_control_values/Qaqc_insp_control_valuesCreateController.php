<?php

namespace App\Http\Controllers\Render\Qaqc_insp_control_values;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Qaqc_insp_control_value;

class Qaqc_insp_control_valuesCreateController extends CreateEditController
{
    protected $type = 'qaqc_insp_control_value';
    protected $data = Qaqc_insp_control_value::class;
    protected $action = "create";
}