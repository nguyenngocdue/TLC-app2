<?php

namespace App\Http\Controllers\Manage\Work_mode;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Work_mode;

class ManageWork_modeTablePropController extends ManageTablePropController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
}
