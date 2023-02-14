<?php

namespace App\Http\Controllers\Entities\Hr_overtime_request_line;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Hr_overtime_request_line;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'hr_overtime_request_line';
    protected $data = Hr_overtime_request_line::class;
}