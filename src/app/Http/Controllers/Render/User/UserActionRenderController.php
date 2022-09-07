<?php

namespace App\Http\Controllers\Render\User;

use App\Http\Controllers\Render\ActionRenderController;
use App\Models\User;

class UserActionRenderController extends ActionRenderController
{
    protected $type = 'user';
    protected $typeModel = User::class;
}
