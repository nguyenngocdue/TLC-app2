<?php

namespace App\Http\Controllers\Entities\Prod_discipline;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Prod_discipline;

class ListenerController extends AbstractListenerController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
}
