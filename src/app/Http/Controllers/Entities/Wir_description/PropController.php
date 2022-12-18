<?php

namespace App\Http\Controllers\Entities\Wir_description;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Wir_description;

class PropController extends AbstractPropController
{
    protected $type = 'wir_description';
    protected $typeModel = Wir_description::class;
}
