<?php

namespace App\Http\Controllers\Entities\Prod_discipline;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Prod_discipline;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'prod_discipline';
    protected $data = Prod_discipline::class;
}