<?php

namespace App\Http\Controllers\Entities\Sub_project;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Sub_project;

class PropController extends AbstractPropController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
}
