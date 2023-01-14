<?php

namespace App\Http\Controllers\Entities\User_type;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\User_type;

class ListenerController extends AbstractListenerController
{
    protected $type = 'user_type';
    protected $typeModel = User_type::class;
}
