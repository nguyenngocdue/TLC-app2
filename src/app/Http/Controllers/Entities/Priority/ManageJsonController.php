<?php

namespace App\Http\Controllers\Entities\Priority;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Priority;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'priority';
    protected $typeModel = Priority::class;
}