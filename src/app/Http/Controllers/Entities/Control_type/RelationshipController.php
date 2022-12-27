<?php

namespace App\Http\Controllers\Entities\Control_type;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Control_type;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'control_type';
    protected $typeModel = Control_type::class;
}
