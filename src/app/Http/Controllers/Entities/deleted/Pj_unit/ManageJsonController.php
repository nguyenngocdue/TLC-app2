<?php

namespace App\Http\Controllers\Entities\Pj_unit;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Pj_unit;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'pj_unit';
    protected $typeModel = Pj_unit::class;
}