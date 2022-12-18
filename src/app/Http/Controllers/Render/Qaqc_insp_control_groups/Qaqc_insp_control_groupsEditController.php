<?php

namespace App\Http\Controllers\Render\Qaqc_insp_control_groups;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Qaqc_insp_control_group;

class Qaqc_insp_control_groupsEditController extends CreateEditController
{
    protected $type = 'qaqc_insp_control_group';
    protected $data = Qaqc_insp_control_group::class;
    protected $action = "edit";

}