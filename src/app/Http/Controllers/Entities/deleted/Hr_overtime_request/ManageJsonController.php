<?php

namespace App\Http\Controllers\Entities\Hr_overtime_request;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Hr_overtime_request;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'hr_overtime_request';
    protected $typeModel = Hr_overtime_request::class;
}