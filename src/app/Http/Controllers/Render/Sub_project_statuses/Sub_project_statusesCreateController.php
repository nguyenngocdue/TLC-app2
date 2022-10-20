<?php

namespace App\Http\Controllers\Render\Sub_project_statuses;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Sub_project_status;

class Sub_project_statusesCreateController extends CreateEditController
{
    protected $type = 'sub_project_status';
    protected $data = Sub_project_status::class;
    protected $action = "create";
}