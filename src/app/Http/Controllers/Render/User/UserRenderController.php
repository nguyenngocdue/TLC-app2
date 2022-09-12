<?php

namespace App\Http\Controllers\Render\User;

use App\Http\Controllers\Render\RenderController;
use App\Models\User;

class UserRenderController extends RenderController
{
    protected $type = 'user';
    protected $data = User::class;
}
