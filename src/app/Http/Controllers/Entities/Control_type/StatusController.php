<?php

namespace App\Http\Controllers\Entities\Control_type;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Control_type;

class StatusController extends AbstractStatusController
{
    protected $type = 'control_type';
    protected $typeModel = Control_type::class;
}