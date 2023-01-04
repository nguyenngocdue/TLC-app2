<?php

namespace App\Http\Controllers\Entities\Hse_corrective_action;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Hse_corrective_action;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'hse_corrective_action';
    protected $data = Hse_corrective_action::class;
    protected $action = "create";
}