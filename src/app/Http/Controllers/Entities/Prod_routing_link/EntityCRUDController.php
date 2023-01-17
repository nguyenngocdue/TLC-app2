<?php

namespace App\Http\Controllers\Entities\Prod_routing_link;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Prod_routing_link;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'prod_routing_link';
    protected $data = Prod_routing_link::class;
}