<?php

namespace App\Http\Controllers\Entities\Control_type;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Control_type;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'control_type';
    protected $typeModel = Control_type::class;
}