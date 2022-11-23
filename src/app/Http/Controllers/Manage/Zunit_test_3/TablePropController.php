<?php

namespace App\Http\Controllers\Manage\Zunit_test_3;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Zunit_test_3;

class TablePropController extends ManageTablePropController
{
    protected $type = 'zunit_test_3';
    protected $typeModel = Zunit_test_3::class;
}
