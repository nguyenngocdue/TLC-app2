<?php

namespace App\Http\Controllers\Render\Sub_projects;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Sub_project;

class Sub_projectsEditController extends CreateEditController
{
    protected $type = 'sub_project';
    protected $data = Sub_project::class;
}