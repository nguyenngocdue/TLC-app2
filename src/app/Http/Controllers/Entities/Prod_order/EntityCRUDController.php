<?php

namespace App\Http\Controllers\Entities\Prod_order;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Prod_order;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'prod_order';
    protected $data = Prod_order::class;
}