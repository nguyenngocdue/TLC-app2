<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Qaqc_insp_tmpl;

class ManageController extends AbstractManageController
{
    protected $type = 'qaqc_insp_tmpl';
    protected $typeModel = Qaqc_insp_tmpl::class;
}