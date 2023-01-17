<?php

namespace App\Http\Controllers\Entities\Hse_corrective_action;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Hse_corrective_action;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'hse_corrective_action';
    protected $typeModel = Hse_corrective_action::class;
}