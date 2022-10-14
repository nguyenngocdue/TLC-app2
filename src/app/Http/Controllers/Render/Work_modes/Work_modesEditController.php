<?php

namespace App\Http\Controllers\Render\Work_modes;

use App\Http\Controllers\Render\EditController;
use App\Models\Work_mode;

class Work_modesEditController extends EditController
{
    protected $type = 'work_mode';
    protected $data = Work_mode::class;
}
