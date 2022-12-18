<?php

namespace App\Http\Controllers\Entities\Wir_description;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Wir_description;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'wir_description';
    protected $typeModel = Wir_description::class;
}
