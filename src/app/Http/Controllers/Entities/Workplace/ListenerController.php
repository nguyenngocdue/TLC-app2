<?php

namespace App\Http\Controllers\Entities\Workplace;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Workplace;

class ListenerController extends AbstractListenerController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}
