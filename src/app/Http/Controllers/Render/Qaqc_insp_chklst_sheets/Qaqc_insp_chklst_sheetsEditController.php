<?php

namespace App\Http\Controllers\Render\Qaqc_insp_chklst_sheets;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Qaqc_insp_chklst_sheet;

class Qaqc_insp_chklst_sheetsEditController extends CreateEditController
{
    protected $type = 'qaqc_insp_chklst_sheet';
    protected $data = Qaqc_insp_chklst_sheet::class;
    protected $action = "edit";

}