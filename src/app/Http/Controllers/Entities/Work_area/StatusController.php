<?php

namespace App\Http\Controllers\Entities\Work_area;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Work_area;

class StatusController extends AbstractStatusController
{
    protected $type = 'work_area';
    protected $typeModel = Work_area::class;
}