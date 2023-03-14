<?php

namespace App\Http\Controllers\Entities\User;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\User;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'user';
    protected $data = User::class;
}