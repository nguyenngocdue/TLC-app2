<?php

namespace App\Http\Controllers\Entities\Project;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Project;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'project';
    protected $data = Project::class;
    protected $action = "create";
}