<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_sheet;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Qaqc_insp_sheet;

class PropController extends AbstractPropController
{
    protected $type = 'qaqc_insp_sheet';
    protected $typeModel = Qaqc_insp_sheet::class;
}
