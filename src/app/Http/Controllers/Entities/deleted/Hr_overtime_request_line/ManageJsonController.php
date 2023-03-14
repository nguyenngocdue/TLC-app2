<?php

namespace App\Http\Controllers\Entities\Hr_overtime_request_line;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Hr_overtime_request_line;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'hr_overtime_request_line';
    protected $typeModel = Hr_overtime_request_line::class;
}