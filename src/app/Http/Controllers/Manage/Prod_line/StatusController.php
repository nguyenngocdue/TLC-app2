<?php

namespace App\Http\Controllers\Manage\Prod_line;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Prod_line;

class StatusController extends ManageStatusController
{
    protected $type = 'prod_line';
    protected $typeModel = Prod_line::class;
}