<?php

namespace App\Http\Controllers\Entities\User_position_pre;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\User_position_pre;

class ListenerController extends AbstractListenerController
{
    protected $type = 'user_position_pre';
    protected $typeModel = User_position_pre::class;
}
