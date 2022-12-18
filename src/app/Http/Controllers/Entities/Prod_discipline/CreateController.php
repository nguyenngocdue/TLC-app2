<?php

namespace App\Http\Controllers\Entities\Prod_discipline;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Prod_discipline;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'prod_discipline';
    protected $data = Prod_discipline::class;
    protected $action = "create";
}