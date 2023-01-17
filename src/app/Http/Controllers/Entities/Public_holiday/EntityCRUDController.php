<?php

namespace App\Http\Controllers\Entities\Public_holiday;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Public_holiday;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'public_holiday';
    protected $data = Public_holiday::class;
}