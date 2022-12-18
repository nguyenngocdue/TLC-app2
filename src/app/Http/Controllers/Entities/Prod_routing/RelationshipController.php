<?php

namespace App\Http\Controllers\Entities\Prod_routing;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Prod_routing;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
}
