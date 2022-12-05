<?php

namespace App\Http\Controllers\Render\Qaqc_insp_chklsts;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Qaqc_insp_chklst;

class Qaqc_insp_chklstsEditController extends CreateEditController
{
    protected $type = 'qaqc_insp_chklst';
    protected $data = Qaqc_insp_chklst::class;
    protected $action = "edit";

}