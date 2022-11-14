<?php

namespace App\Http\Controllers\Manage\Sub_project_status;

use App\Http\Controllers\Manage\ManagePropController;

class ManageSub_project_statusPropController extends ManagePropController
{
    protected $type = 'sub_project_status';
    protected $typeModel = Sub_project_status::class;
}
