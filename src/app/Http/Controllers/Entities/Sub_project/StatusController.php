<?php

namespace App\Http\Controllers\Entities\Sub_project;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Sub_project;

class StatusController extends AbstractStatusController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
}