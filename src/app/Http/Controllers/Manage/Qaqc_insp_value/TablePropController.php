<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_value;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Qaqc_insp_value;

class TablePropController extends ManageTablePropController
{
    protected $type = 'qaqc_insp_value';
    protected $typeModel = Qaqc_insp_value::class;
}
