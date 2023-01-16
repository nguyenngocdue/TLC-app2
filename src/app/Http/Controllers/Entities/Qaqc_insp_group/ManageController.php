<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_group;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Qaqc_insp_group;

class ManageController extends AbstractManageController
{
    protected $type = 'qaqc_insp_group';
    protected $typeModel = Qaqc_insp_group::class;
}