<?php

namespace App\Http\Controllers\Manage\Prod_routing_link;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Prod_routing_link;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'prod_routing_link';
    protected $typeModel = Prod_routing_link::class;
}
