<?php

namespace App\Http\Controllers\Entities\Hse_corrective_action;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Hse_corrective_action;

class ManageController extends AbstractManageController
{
    protected $type = 'hse_corrective_action';
    protected $typeModel = Hse_corrective_action::class;
}