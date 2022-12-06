<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_group;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Qaqc_insp_group;

class StatusController extends ManageStatusController
{
    protected $type = 'qaqc_insp_group';
    protected $typeModel = Qaqc_insp_group::class;
}
