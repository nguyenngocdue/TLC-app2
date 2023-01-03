<?php

namespace App\Http\Controllers\Entities\Work_area;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Work_area;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'work_area';
    protected $data = Work_area::class;
    protected $action = "create";
}