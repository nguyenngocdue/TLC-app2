<?php

namespace App\Http\Controllers\Manage\Wir_description;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Wir_description;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'wir_description';
    protected $typeModel = Wir_description::class;
}
