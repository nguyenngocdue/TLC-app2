<?php

namespace App\Http\Controllers\Entities\Prod_routing;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Prod_routing;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'prod_routing';
    protected $data = Prod_routing::class;
}