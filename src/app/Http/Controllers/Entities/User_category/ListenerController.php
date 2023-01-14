<?php

namespace App\Http\Controllers\Entities\User_category;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\User_category;

class ListenerController extends AbstractListenerController
{
    protected $type = 'user_category';
    protected $typeModel = User_category::class;
}
