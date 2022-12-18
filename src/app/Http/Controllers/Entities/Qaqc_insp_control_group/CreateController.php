<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_control_group;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Qaqc_insp_control_group;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'qaqc_insp_control_group';
    protected $data = Qaqc_insp_control_group::class;
    protected $action = "create";
}