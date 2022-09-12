<?php

namespace App\Http\Controllers\Manage\Workplace;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Workplace;

class ManageWorkplaceRelationshipController extends ManageRelationshipController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}
