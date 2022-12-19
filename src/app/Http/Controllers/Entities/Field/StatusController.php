<?php

namespace App\Http\Controllers\Entities\Field;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Field;

class StatusController extends AbstractStatusController
{
    protected $type = 'field';
    protected $typeModel = Field::class;
}