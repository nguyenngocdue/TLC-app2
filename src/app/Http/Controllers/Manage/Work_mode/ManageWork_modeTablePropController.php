<?php

namespace App\Http\Controllers\Manage\Work_mode;

use App\Http\Controllers\Manage\ManageTablePropController;

class ManageWork_modeTablePropController extends ManageTablePropController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
}
