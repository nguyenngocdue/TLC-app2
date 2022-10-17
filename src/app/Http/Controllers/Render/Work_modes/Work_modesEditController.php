<?php

namespace App\Http\Controllers\Render\Work_modes;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Work_mode;

class Work_modesEditController extends CreateEditController
{
    protected $type = 'work_mode';
    protected $data = Work_mode::class;
    protected $action = "edit";
}
