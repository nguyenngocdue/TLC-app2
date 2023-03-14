<?php

namespace App\Http\Controllers\Entities\Work_mode;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Work_mode;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
}