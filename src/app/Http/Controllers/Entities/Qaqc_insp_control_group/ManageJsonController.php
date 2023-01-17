<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_control_group;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Qaqc_insp_control_group;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'qaqc_insp_control_group';
    protected $typeModel = Qaqc_insp_control_group::class;
}