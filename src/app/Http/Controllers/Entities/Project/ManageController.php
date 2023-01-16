<?php

namespace App\Http\Controllers\Entities\Project;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Project;

class ManageController extends AbstractManageController
{
    protected $type = 'project';
    protected $typeModel = Project::class;
}