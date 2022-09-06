<?php

namespace App\Http\Controllers\Render\User;

use App\Http\Controllers\Render\DeleteRenderController;

class DeleteRenderUserController extends DeleteRenderController
{
    protected $type = 'user';
    protected $data = App\Models\User::class;
}
