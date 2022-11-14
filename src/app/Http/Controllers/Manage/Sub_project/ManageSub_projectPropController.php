<?php

namespace App\Http\Controllers\Manage\Sub_project;

use App\Http\Controllers\Manage\ManagePropController;

class ManageSub_projectPropController extends ManagePropController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
}
