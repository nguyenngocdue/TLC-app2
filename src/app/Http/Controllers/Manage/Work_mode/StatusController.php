<?php

namespace App\Http\Controllers\Manage\Work_mode;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Work_mode;

class StatusController extends ManageStatusController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
}