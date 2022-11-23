<?php

namespace App\Http\Controllers\Manage\Work_mode;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Work_mode;

class PropController extends ManagePropController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
}
