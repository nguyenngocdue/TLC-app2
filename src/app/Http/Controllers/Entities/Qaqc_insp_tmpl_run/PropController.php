<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl_run;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Qaqc_insp_tmpl_run;

class PropController extends AbstractPropController
{
    protected $type = 'qaqc_insp_tmpl_run';
    protected $typeModel = Qaqc_insp_tmpl_run::class;
}
