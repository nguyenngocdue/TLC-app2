<?php

namespace App\Http\Controllers\Manage\Zunit_test_2;

use App\Http\Controllers\Manage\ManagePropController;
use App\Models\Zunit_test_2;

class PropController extends ManagePropController
{
    protected $type = 'zunit_test_2';
    protected $typeModel = Zunit_test_2::class;
}
