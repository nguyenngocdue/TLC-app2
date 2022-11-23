<?php

namespace App\Http\Controllers\Manage\Prod_user_run;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Prod_user_run;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'prod_user_run';
    protected $typeModel = Prod_user_run::class;
}
