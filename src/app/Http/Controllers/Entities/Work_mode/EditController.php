<?php

namespace App\Http\Controllers\Entities\Work_mode;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Work_mode;

class EditController extends AbstractCreateEditController
{
    protected $type = 'work_mode';
    protected $data = Work_mode::class;
    protected $action = "edit";

}