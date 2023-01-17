<?php

namespace App\Http\Controllers\Entities\Workplace;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Workplace;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}