<?php

namespace App\Http\Controllers\Entities\Qaqc_wir;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Qaqc_wir;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'qaqc_wir';
    protected $typeModel = Qaqc_wir::class;
}