<?php

namespace App\Http\Controllers\Manage\Workplace;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Workplace;

class PropController extends ManagePropController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}
