<?php

namespace App\Http\Controllers\Entities\Field;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Field;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'field';
    protected $data = Field::class;
}