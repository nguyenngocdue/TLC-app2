<?php

namespace App\Http\Controllers\Entities\Prod_order;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Prod_order;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
}
