<?php

namespace App\Http\Controllers\Render\Qaqc_insp_tmpls;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Qaqc_insp_tmpl;

class Qaqc_insp_tmplsEditController extends CreateEditController
{
    protected $type = 'qaqc_insp_tmpl';
    protected $data = Qaqc_insp_tmpl::class;
    protected $action = "edit";

}