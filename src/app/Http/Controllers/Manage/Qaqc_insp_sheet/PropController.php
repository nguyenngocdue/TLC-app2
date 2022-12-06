<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_sheet;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Qaqc_insp_sheet;

class PropController extends ManagePropController
{
    protected $type = 'qaqc_insp_sheet';
    protected $typeModel = Qaqc_insp_sheet::class;
}
