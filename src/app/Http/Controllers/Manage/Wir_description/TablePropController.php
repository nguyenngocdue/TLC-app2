<?php

namespace App\Http\Controllers\Manage\Wir_description;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Wir_description;

class TablePropController extends ManageTablePropController
{
    protected $type = 'wir_description';
    protected $typeModel = Wir_description::class;
}
