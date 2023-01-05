<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst_run;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Qaqc_insp_chklst_run;

class EditController extends AbstractCreateEditController
{
    protected $type = 'qaqc_insp_chklst_run';
    protected $data = Qaqc_insp_chklst_run::class;
    protected $action = "edit";

}