<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_chklst_sheet;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Qaqc_insp_chklst_sheet;

class StatusController extends ManageStatusController
{
    protected $type = 'qaqc_insp_chklst_sheet';
    protected $typeModel = Qaqc_insp_chklst_sheet::class;
}