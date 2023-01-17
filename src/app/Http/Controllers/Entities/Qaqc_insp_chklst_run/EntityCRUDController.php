<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst_run;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Qaqc_insp_chklst_run;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'qaqc_insp_chklst_run';
    protected $data = Qaqc_insp_chklst_run::class;
}