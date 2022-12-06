<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_tmpl_line;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Qaqc_insp_tmpl_line;

class PropController extends ManagePropController
{
    protected $type = 'qaqc_insp_tmpl_line';
    protected $typeModel = Qaqc_insp_tmpl_line::class;
}
