<?php

namespace App\Http\Controllers\Render\Qaqc_insp_sheets;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Qaqc_insp_sheet;

class Qaqc_insp_sheetsEditController extends CreateEditController
{
    protected $type = 'qaqc_insp_sheet';
    protected $data = Qaqc_insp_sheet::class;
    protected $action = "edit";
}
