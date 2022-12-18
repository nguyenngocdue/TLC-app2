<?php

namespace App\Http\Controllers\Entities\Erp_routing_link;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Erp_routing_link;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'erp_routing_link';
    protected $typeModel = Erp_routing_link::class;
}
