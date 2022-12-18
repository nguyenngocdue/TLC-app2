<?php

namespace App\Http\Controllers\Entities\Prod_routing_detail;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Prod_routing_detail;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'prod_routing_detail';
    protected $typeModel = Prod_routing_detail::class;
}
