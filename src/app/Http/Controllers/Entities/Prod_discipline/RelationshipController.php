<?php

namespace App\Http\Controllers\Entities\Prod_discipline;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Prod_discipline;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
}
