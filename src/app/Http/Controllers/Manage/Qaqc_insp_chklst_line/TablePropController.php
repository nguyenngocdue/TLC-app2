<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_chklst_line;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Qaqc_insp_chklst_line;

class TablePropController extends ManageTablePropController
{
    protected $type = 'qaqc_insp_chklst_line';
    protected $typeModel = Qaqc_insp_chklst_line::class;
}
