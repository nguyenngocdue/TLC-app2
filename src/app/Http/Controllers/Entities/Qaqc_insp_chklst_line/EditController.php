<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_chklst_line;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Qaqc_insp_chklst_line;

class EditController extends AbstractCreateEditController
{
    protected $type = 'qaqc_insp_chklst_line';
    protected $data = Qaqc_insp_chklst_line::class;
    protected $action = "edit";

}