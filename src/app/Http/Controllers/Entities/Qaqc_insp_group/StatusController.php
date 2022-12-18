<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_group;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Qaqc_insp_group;

class StatusController extends AbstractStatusController
{
    protected $type = 'qaqc_insp_group';
    protected $typeModel = Qaqc_insp_group::class;
}