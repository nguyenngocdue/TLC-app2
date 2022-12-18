<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl_line;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Qaqc_insp_tmpl_line;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'qaqc_insp_tmpl_line';
    protected $data = Qaqc_insp_tmpl_line::class;
    protected $action = "create";
}