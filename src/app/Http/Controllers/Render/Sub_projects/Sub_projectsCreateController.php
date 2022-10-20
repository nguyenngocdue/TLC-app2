<?php

namespace App\Http\Controllers\Render\Sub_projects;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Sub_project;

class Sub_projectsCreateController extends CreateEditController
{
    protected $type = 'sub_project';
    protected $data = Sub_project::class;
    protected $action = "create";
}