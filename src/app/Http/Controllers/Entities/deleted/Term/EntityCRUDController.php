<?php

namespace App\Http\Controllers\Entities\Term;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Term;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'term';
    protected $data = Term::class;
}