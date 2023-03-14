<?php

namespace App\Http\Controllers\Entities\Pj_pod;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Pj_pod;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'pj_pod';
    protected $typeModel = Pj_pod::class;
}