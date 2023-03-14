<?php

namespace App\Http\Controllers\Entities\Work_area;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Work_area;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'work_area';
    protected $typeModel = Work_area::class;
}