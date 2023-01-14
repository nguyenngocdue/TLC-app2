<?php

namespace App\Http\Controllers\Entities\User_position2;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\User_position2;

class ListenerController extends AbstractListenerController
{
    protected $type = 'user_position2';
    protected $typeModel = User_position2::class;
}
