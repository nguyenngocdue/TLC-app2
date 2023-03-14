<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_tmpl;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Qaqc_insp_tmpl;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'qaqc_insp_tmpl';
    protected $typeModel = Qaqc_insp_tmpl::class;
}