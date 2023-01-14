<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_control_group;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Qaqc_insp_control_group;

class ListenerController extends AbstractListenerController
{
    protected $type = 'qaqc_insp_control_group';
    protected $typeModel = Qaqc_insp_control_group::class;
}
