<?php

namespace App\Http\Controllers\Entities\Project;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Project;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'project';
    protected $typeModel = Project::class;
}