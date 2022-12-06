<?php

namespace App\Http\Controllers\Render\Qaqc_insp_groups;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Qaqc_insp_group;

class Qaqc_insp_groupsCreateController extends CreateEditController
{
    protected $type = 'qaqc_insp_group';
    protected $data = Qaqc_insp_group::class;
    protected $action = "create";
}
