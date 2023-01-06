<?php

namespace App\Http\Controllers\Entities\Public_holiday;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Public_holiday;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'public_holiday';
    protected $typeModel = Public_holiday::class;
}
