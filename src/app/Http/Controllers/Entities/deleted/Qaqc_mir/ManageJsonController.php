<?php

namespace App\Http\Controllers\Entities\Qaqc_mir;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Qaqc_mir;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'qaqc_mir';
    protected $typeModel = Qaqc_mir::class;
}