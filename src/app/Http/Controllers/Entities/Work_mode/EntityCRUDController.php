<?php

namespace App\Http\Controllers\Entities\Work_mode;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Work_mode;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'work_mode';
    protected $data = Work_mode::class;
}