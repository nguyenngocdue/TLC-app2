<?php

namespace App\Http\Controllers\Entities\Prod_run;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Prod_run;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'prod_run';
    protected $typeModel = Prod_run::class;
}
