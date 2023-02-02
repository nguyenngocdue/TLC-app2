<?php

namespace App\Http\Controllers\Entities\Qaqc_ncr;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Qaqc_ncr;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'qaqc_ncr';
    protected $typeModel = Qaqc_ncr::class;
}