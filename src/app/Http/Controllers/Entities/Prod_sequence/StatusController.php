<?php

namespace App\Http\Controllers\Entities\Prod_sequence;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Prod_sequence;

class StatusController extends AbstractStatusController
{
    protected $type = 'prod_sequence';
    protected $typeModel = Prod_sequence::class;
}