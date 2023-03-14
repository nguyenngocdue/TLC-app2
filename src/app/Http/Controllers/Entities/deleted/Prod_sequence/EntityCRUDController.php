<?php

namespace App\Http\Controllers\Entities\Prod_sequence;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Prod_sequence;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'prod_sequence';
    protected $data = Prod_sequence::class;
}