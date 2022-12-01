<?php

namespace App\Http\Controllers\Manage\Prod_run_line;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Prod_run_line;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'prod_run_line';
    protected $typeModel = Prod_run_line::class;
}
