<?php

namespace App\Http\Controllers\Manage\Work_mode;

use App\Http\Controllers\Manage\ManagePropController;

class ManageWork_modePropController extends ManagePropController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
}
