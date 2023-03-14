<?php

namespace App\Http\Controllers\Entities\Pj_module;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Pj_module;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'pj_module';
    protected $typeModel = Pj_module::class;
}