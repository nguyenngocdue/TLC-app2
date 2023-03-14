<?php

namespace App\Http\Controllers\Entities\Pj_staircase;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Pj_staircase;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'pj_staircase';
    protected $typeModel = Pj_staircase::class;
}