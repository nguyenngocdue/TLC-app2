<?php

namespace App\Http\Controllers\Entities\Qaqc_insp_value;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Qaqc_insp_value;

class ListenerController extends AbstractListenerController
{
    protected $type = 'qaqc_insp_value';
    protected $typeModel = Qaqc_insp_value::class;
}
