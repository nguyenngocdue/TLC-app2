<?php

namespace App\Http\Controllers\Entities\Prod_sequence;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Prod_sequence;

class PropController extends AbstractPropController
{
    protected $type = 'prod_sequence';
    protected $typeModel = Prod_sequence::class;
}
