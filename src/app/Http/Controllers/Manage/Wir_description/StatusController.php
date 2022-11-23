<?php

namespace App\Http\Controllers\Manage\Wir_description;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Wir_description;

class StatusController extends ManageStatusController
{
    protected $type = 'wir_description';
    protected $typeModel = Wir_description::class;
}