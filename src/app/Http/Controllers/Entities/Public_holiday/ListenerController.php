<?php

namespace App\Http\Controllers\Entities\Public_holiday;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Public_holiday;

class ListenerController extends AbstractListenerController
{
    protected $type = 'public_holiday';
    protected $typeModel = Public_holiday::class;
}
