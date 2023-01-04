<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst_sht;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Qaqc_insp_chklst_sht;

class PropController extends AbstractPropController
{
    protected $type = 'qaqc_insp_chklst_sht';
    protected $typeModel = Qaqc_insp_chklst_sht::class;
}
