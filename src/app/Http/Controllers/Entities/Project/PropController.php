<?php

namespace App\Http\Controllers\Entities\Project;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Project;

class PropController extends AbstractPropController
{
    protected $type = 'project';
    protected $typeModel = Project::class;
}
