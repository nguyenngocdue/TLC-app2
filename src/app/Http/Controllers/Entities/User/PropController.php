<?php

namespace App\Http\Controllers\Entities\User;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\User;

class PropController extends AbstractPropController
{
    protected $type = 'user';
    protected $typeModel = User::class;
}
