<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_sheet;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Qaqc_insp_sheet;

class StatusController extends AbstractStatusController
{
    protected $type = 'qaqc_insp_sheet';
    protected $typeModel = Qaqc_insp_sheet::class;
}