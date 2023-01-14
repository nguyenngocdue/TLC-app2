<?php

namespace App\Http\Controllers\Entities\Prod_sequence;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Prod_sequence;

class ListenerController extends AbstractListenerController
{
    protected $type = 'prod_sequence';
    protected $typeModel = Prod_sequence::class;
}
