<?php

namespace App\Http\Controllers\Render\User_disciplines;

use App\Http\Controllers\Render\EditController;
use App\Models\User_discipline;

class User_disciplinesCreateController extends EditController
{
    protected $type = 'user_discipline';
    protected $data = User_discipline::class;
    protected $action = "create";
}