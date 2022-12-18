<?php

namespace App\Http\Controllers\Entities\Wir_description;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Wir_description;

class StatusController extends AbstractStatusController
{
    protected $type = 'wir_description';
    protected $typeModel = Wir_description::class;
}