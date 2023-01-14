<?php

namespace App\Http\Controllers\Entities\Field;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Field;

class ListenerController extends AbstractListenerController
{
    protected $type = 'field';
    protected $typeModel = Field::class;
}
