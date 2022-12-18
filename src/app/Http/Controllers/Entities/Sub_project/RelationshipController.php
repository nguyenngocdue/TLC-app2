<?php

namespace App\Http\Controllers\Entities\Sub_project;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Sub_project;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
}
