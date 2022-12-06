<?php

namespace App\Http\Controllers\Render\Qaqc_insp_chklst_groups;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Qaqc_insp_chklst_group;

class Qaqc_insp_chklst_groupsCreateController extends CreateEditController
{
    protected $type = 'qaqc_insp_chklst_group';
    protected $data = Qaqc_insp_chklst_group::class;
    protected $action = "create";
}