<?php

namespace App\Http\Controllers\Render\User_disciplines;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\User_discipline;

class User_disciplinesEditController extends CreateEditController
{
    protected $type = 'user_discipline';
    protected $data = User_discipline::class;
    protected $action = "edit";
}
