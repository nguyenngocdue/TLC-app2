<?php

namespace App\Http\Controllers\Render\Qaqc_insp_master_lists;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Qaqc_insp_master_list;

class Qaqc_insp_master_listsCreateController extends CreateEditController
{
    protected $type = 'qaqc_insp_master_list';
    protected $data = Qaqc_insp_master_list::class;
    protected $action = "create";
}