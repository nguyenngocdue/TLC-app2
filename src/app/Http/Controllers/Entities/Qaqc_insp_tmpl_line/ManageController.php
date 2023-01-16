<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl_line;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Qaqc_insp_tmpl_line;

class ManageController extends AbstractManageController
{
    protected $type = 'qaqc_insp_tmpl_line';
    protected $typeModel = Qaqc_insp_tmpl_line::class;
}