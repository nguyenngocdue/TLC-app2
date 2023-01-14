<?php

namespace App\Http\Controllers\Entities\Wir_description;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Wir_description;

class ListenerController extends AbstractListenerController
{
    protected $type = 'wir_description';
    protected $typeModel = Wir_description::class;
}
