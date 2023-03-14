<?php

namespace App\Http\Controllers\Entities\User_category;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\User_category;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'user_category';
    protected $data = User_category::class;
}