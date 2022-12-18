<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Qaqc_insp_tmpl;

class EditController extends AbstractCreateEditController
{
    protected $type = 'qaqc_insp_tmpl';
    protected $data = Qaqc_insp_tmpl::class;
    protected $action = "edit";

}