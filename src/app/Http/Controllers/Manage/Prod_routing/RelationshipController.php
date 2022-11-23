<?php

namespace App\Http\Controllers\Manage\Prod_routing;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Prod_routing;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'prod_routing';
    protected $typeModel = Prod_routing::class;
}
