<?php

namespace App\Http\Controllers\Entities\Hr_overtime_request;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Hr_overtime_request;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'hr_overtime_request';
    protected $data = Hr_overtime_request::class;
}