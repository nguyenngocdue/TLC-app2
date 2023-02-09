<?php

namespace App\Http\Controllers\Entities\Pj_module;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Pj_module;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'pj_module';
    protected $data = Pj_module::class;
}