<?php

namespace App\Http\Controllers\Manage\Workplace;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Workplace;

class ManageWorkplaceTablePropController extends ManageTablePropController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}
