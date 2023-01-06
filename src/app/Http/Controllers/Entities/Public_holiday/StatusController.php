<?php

namespace App\Http\Controllers\Entities\Public_holiday;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Public_holiday;

class StatusController extends AbstractStatusController
{
    protected $type = 'public_holiday';
    protected $typeModel = Public_holiday::class;
}