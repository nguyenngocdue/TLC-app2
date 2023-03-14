<?php

namespace App\Http\Controllers\Entities\Erp_routing_link;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Erp_routing_link;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'erp_routing_link';
    protected $data = Erp_routing_link::class;
}