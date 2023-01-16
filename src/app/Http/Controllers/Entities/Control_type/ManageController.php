<?php

namespace App\Http\Controllers\Entities\Control_type;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Control_type;

class ManageController extends AbstractManageController
{
    protected $type = 'control_type';
    protected $typeModel = Control_type::class;
}