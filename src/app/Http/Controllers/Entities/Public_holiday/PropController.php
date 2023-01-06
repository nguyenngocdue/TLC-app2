<?php

namespace App\Http\Controllers\Entities\Public_holiday;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Public_holiday;

class PropController extends AbstractPropController
{
    protected $type = 'public_holiday';
    protected $typeModel = Public_holiday::class;
}
