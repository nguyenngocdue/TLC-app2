<?php

namespace App\Http\Controllers\Entities\Field;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Field;

class PropController extends AbstractPropController
{
    protected $type = 'field';
    protected $typeModel = Field::class;
}
