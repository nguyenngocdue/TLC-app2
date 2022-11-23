<?php

namespace App\Http\Controllers\Manage\Erp_routing_link;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Erp_routing_link;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'erp_routing_link';
    protected $typeModel = Erp_routing_link::class;
}
