<?php

namespace App\Http\Controllers\Entities\Term;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Term;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'term';
    protected $typeModel = Term::class;
}
