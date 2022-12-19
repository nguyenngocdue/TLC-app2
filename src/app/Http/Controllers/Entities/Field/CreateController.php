<?php

namespace App\Http\Controllers\Entities\Field;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Field;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'field';
    protected $data = Field::class;
    protected $action = "create";
}