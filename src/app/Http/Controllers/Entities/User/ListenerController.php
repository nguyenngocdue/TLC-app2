<?php

namespace App\Http\Controllers\Entities\User;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\User;

class ListenerController extends AbstractListenerController
{
    protected $type = 'user';
    protected $typeModel = User::class;
}
