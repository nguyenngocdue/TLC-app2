<?php

namespace App\Http\Controllers\Manage\Prod_discipline;

use App\Http\Controllers\Manage\ManageTablePropController;

class ManageProd_disciplineTablePropController extends ManageTablePropController
{
    protected $type = 'prod_discipline';
    protected $typeModel = Prod_discipline::class;
}
