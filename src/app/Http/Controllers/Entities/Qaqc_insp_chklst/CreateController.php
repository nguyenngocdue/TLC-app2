<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Qaqc_insp_chklst;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'qaqc_insp_chklst';
    protected $data = Qaqc_insp_chklst::class;
    protected $action = "create";
}