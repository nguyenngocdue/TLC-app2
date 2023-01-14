<?php

namespace App\Http\Controllers\Entities\Work_area;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Work_area;

class ListenerController extends AbstractListenerController
{
    protected $type = 'work_area';
    protected $typeModel = Work_area::class;
}
