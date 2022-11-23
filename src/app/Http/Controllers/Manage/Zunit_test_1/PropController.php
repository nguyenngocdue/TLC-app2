<?php

namespace App\Http\Controllers\Manage\Zunit_test_1;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Zunit_test_1;

class PropController extends ManagePropController
{
    protected $type = 'zunit_test_1';
    protected $typeModel = Zunit_test_1::class;
}
