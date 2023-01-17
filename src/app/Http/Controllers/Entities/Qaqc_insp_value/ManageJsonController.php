<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_value;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Qaqc_insp_value;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'qaqc_insp_value';
    protected $typeModel = Qaqc_insp_value::class;
}