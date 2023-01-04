<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl_sht;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Qaqc_insp_tmpl_sht;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'qaqc_insp_tmpl_sht';
    protected $data = Qaqc_insp_tmpl_sht::class;
    protected $action = "create";
}