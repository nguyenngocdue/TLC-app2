<?php

namespace App\Http\Controllers\Manage\Sub_project_status;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Sub_project_status;

class StatusController extends ManageStatusController
{
    protected $type = 'sub_project_status';
    protected $typeModel = Sub_project_status::class;
}