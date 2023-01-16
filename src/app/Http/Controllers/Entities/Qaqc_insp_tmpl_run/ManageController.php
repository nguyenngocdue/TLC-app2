<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl_run;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Qaqc_insp_tmpl_run;

class ManageController extends AbstractManageController
{
    protected $type = 'qaqc_insp_tmpl_run';
    protected $typeModel = Qaqc_insp_tmpl_run::class;
}