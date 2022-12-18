<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_value;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Qaqc_insp_value;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'qaqc_insp_value';
    protected $data = Qaqc_insp_value::class;
    protected $action = "create";
}