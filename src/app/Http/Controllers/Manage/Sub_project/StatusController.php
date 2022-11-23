<?php

namespace App\Http\Controllers\Manage\Sub_project;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Sub_project;

class StatusController extends ManageStatusController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
}