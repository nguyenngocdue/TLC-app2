<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst_run;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Qaqc_insp_chklst_run;

class PropController extends AbstractPropController
{
    protected $type = 'qaqc_insp_chklst_run';
    protected $typeModel = Qaqc_insp_chklst_run::class;
}
