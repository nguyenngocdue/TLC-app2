<?php

namespace App\Http\Controllers\Manage\Zunit_test_1;

use App\Http\Controllers\Manage\ManageTablePropController;
use App\Models\Zunit_test_1;

class ManageZunit_test_1TablePropController extends ManageTablePropController
{
    protected $type = 'zunit_test_1';
    protected $typeModel = Zunit_test_1::class;
}
