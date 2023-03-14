<?php

namespace App\Http\Controllers\Entities\Priority;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Priority;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'priority';
    protected $data = Priority::class;
}