<?php

namespace App\Http\Controllers\Entities\User_discipline;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\User_discipline;

class ListenerController extends AbstractListenerController
{
    protected $type = 'user_discipline';
    protected $typeModel = User_discipline::class;
}
