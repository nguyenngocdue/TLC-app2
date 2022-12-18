<?php

namespace App\Http\Controllers\Entities\Workplace;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Workplace;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'workplace';
    protected $data = Workplace::class;
    protected $action = "create";
}