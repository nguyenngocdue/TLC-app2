<?php

namespace App\Http\Controllers\Manage\Prod_line;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Prod_line;

class ManageProd_lineTablePropController extends ManageTablePropController
{
    protected $type = 'prod_line';
    protected $typeModel = Prod_line::class;
}
