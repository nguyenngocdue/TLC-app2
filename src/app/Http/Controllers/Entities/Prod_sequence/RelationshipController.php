<?php

namespace App\Http\Controllers\Entities\Prod_sequence;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Prod_sequence;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'prod_sequence';
    protected $typeModel = Prod_sequence::class;
}
