<?php

namespace App\Http\Controllers\Entities\Wir_description;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Wir_description;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'wir_description';
    protected $data = Wir_description::class;
}