<?php

namespace App\Http\Controllers\Entities\Prod_run;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Prod_run;

class ListenerController extends AbstractListenerController
{
    protected $type = 'prod_run';
    protected $typeModel = Prod_run::class;
}
