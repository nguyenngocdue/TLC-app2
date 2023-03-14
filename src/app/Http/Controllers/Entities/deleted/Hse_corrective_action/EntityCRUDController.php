<?php

namespace App\Http\Controllers\Entities\Hse_corrective_action;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Hse_corrective_action;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'hse_corrective_action';
    protected $data = Hse_corrective_action::class;
}