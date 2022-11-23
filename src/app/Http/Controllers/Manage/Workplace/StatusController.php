<?php

namespace App\Http\Controllers\Manage\Workplace;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Workplace;

class StatusController extends ManageStatusController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}