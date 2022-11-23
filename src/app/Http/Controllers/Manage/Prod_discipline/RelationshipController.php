<?php

namespace App\Http\Controllers\Manage\Prod_discipline;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Prod_discipline;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
}
