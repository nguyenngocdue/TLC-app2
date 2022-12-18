<?php

namespace App\Http\Controllers\Entities\Sub_project;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Sub_project;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'sub_project';
    protected $data = Sub_project::class;
    protected $action = "create";
}