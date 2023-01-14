<?php

namespace App\Http\Controllers\Entities\Work_mode;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Work_mode;

class ListenerController extends AbstractListenerController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
}
