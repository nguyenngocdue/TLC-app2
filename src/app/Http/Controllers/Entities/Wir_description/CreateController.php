<?php

namespace App\Http\Controllers\Entities\Wir_description;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Wir_description;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'wir_description';
    protected $data = Wir_description::class;
    protected $action = "create";
}