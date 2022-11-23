<?php

namespace App\Http\Controllers\Manage\Workplace;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Workplace;

class TablePropController extends ManageTablePropController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}
