<?php

namespace App\Http\Controllers\Render\User;

use App\Http\Controllers\Render\ActionRenderController;

class UserActionRenderController extends ActionRenderController
{
    protected $type = 'user';
    protected $data = App\Models\User::class;
}
