<?php

namespace App\Http\Controllers\Manage\Prod_line;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Prod_line;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'prod_line';
    protected $typeModel = Prod_line::class;
}
