<?php

namespace App\Http\Controllers\Manage\Sub_project;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Sub_project;

class TablePropController extends ManageTablePropController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
}
