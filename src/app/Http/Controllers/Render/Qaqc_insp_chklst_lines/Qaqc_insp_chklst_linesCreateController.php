<?php

namespace App\Http\Controllers\Render\Qaqc_insp_chklst_lines;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Qaqc_insp_chklst_line;

class Qaqc_insp_chklst_linesCreateController extends CreateEditController
{
    protected $type = 'qaqc_insp_chklst_line';
    protected $data = Qaqc_insp_chklst_line::class;
    protected $action = "create";
}