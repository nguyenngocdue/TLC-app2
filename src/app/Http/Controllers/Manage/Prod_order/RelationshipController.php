<?php

namespace App\Http\Controllers\Manage\Prod_order;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Prod_order;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'prod_order';
    protected $typeModel = Prod_order::class;
}
