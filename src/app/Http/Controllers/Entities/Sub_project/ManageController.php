<?php

namespace App\Http\Controllers\Entities\Sub_project;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Sub_project;

class ManageController extends AbstractManageController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
}