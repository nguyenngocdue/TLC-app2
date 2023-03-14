<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst_sht;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Qaqc_insp_chklst_sht;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'qaqc_insp_chklst_sht';
    protected $typeModel = Qaqc_insp_chklst_sht::class;
}