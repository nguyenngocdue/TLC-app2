<?php

namespace App\Http\Controllers\Manage\Prod_run;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Prod_run;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'prod_run';
    protected $typeModel = Prod_run::class;
}
