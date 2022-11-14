<?php

namespace App\Http\Controllers\Manage\Sub_project;

use App\Http\Controllers\Manage\ManageTablePropController;

class ManageSub_projectTablePropController extends ManageTablePropController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
}
