<?php

namespace App\Http\Controllers\Entities\Project;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Project;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'project';
    protected $typeModel = Project::class;
}
