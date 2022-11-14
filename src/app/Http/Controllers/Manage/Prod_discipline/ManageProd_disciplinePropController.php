<?php

namespace App\Http\Controllers\Manage\Prod_discipline;

use App\Http\Controllers\Manage\ManagePropController;

class ManageProd_disciplinePropController extends ManagePropController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
}
