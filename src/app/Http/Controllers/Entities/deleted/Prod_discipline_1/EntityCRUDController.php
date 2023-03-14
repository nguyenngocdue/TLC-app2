<?php

namespace App\Http\Controllers\Entities\Prod_discipline_1;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Prod_discipline_1;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'prod_discipline_1';
    protected $data = Prod_discipline_1::class;
}