<?php

namespace App\Http\Controllers\Entities\Control_type;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Control_type;

class ListenerController extends AbstractListenerController
{
    protected $type = 'control_type';
    protected $typeModel = Control_type::class;
}
