<?php

namespace App\Http\Controllers\Entities\Work_mode;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Work_mode;

class StatusController extends AbstractStatusController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
}