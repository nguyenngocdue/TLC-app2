<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_sheet;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Qaqc_insp_sheet;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'qaqc_insp_sheet';
    protected $data = Qaqc_insp_sheet::class;
    protected $action = "create";
}