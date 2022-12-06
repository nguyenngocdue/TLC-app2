<?php

namespace App\Http\Controllers\Render\Qaqc_insp_tmpl_lines;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Qaqc_insp_tmpl_line;

class Qaqc_insp_tmpl_linesCreateController extends CreateEditController
{
    protected $type = 'qaqc_insp_tmpl_line';
    protected $data = Qaqc_insp_tmpl_line::class;
    protected $action = "create";
}