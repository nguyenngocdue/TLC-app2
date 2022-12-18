<?php

namespace App\Http\Controllers\Entities\Workplace;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Workplace;

class StatusController extends AbstractStatusController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}