<?php

namespace App\Http\Controllers\Entities\Project;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Project;

class StatusController extends AbstractStatusController
{
    protected $type = 'project';
    protected $typeModel = Project::class;
}