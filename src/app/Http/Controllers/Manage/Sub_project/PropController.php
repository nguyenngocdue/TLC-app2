<?php

namespace App\Http\Controllers\Manage\Sub_project;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Sub_project;

class PropController extends ManagePropController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
}
