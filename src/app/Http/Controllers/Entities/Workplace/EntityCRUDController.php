<?php

namespace App\Http\Controllers\Entities\Workplace;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Workplace;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'workplace';
    protected $data = Workplace::class;
}