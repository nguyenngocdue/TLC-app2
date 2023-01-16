<?php

namespace App\Http\Controllers\Entities\Work_area;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Work_area;

class ManageController extends AbstractManageController
{
    protected $type = 'work_area';
    protected $typeModel = Work_area::class;
}