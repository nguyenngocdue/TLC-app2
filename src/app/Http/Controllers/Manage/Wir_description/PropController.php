<?php

namespace App\Http\Controllers\Manage\Wir_description;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Wir_description;

class PropController extends ManagePropController
{
    protected $type = 'wir_description';
    protected $typeModel = Wir_description::class;
}
