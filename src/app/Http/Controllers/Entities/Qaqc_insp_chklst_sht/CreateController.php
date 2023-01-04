<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst_sht;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Qaqc_insp_chklst_sht;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'qaqc_insp_chklst_sht';
    protected $data = Qaqc_insp_chklst_sht::class;
    protected $action = "create";
}