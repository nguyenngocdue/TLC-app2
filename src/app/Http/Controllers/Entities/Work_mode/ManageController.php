<?php

namespace App\Http\Controllers\Entities\Work_mode;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Work_mode;

class ManageController extends AbstractManageController
{
    protected $type = 'work_mode';
    protected $typeModel = Work_mode::class;
}