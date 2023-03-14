<?php

namespace App\Http\Controllers\Entities\Sub_project;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Sub_project;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
}