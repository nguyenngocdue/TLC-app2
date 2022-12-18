<?php

namespace App\Http\Controllers\Entities\Sub_project;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Sub_project;

class EditController extends AbstractCreateEditController
{
    protected $type = 'sub_project';
    protected $data = Sub_project::class;
    protected $action = "edit";

}