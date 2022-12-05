<?php

namespace App\Http\Controllers\Manage\Qaqc_insp_master_list;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Qaqc_insp_master_list;

class PropController extends ManagePropController
{
    protected $type = 'qaqc_insp_master_list';
    protected $typeModel = Qaqc_insp_master_list::class;
}
