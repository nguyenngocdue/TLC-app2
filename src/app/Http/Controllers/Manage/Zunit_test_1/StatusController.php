<?php

namespace App\Http\Controllers\Manage\Zunit_test_1;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Zunit_test_1;

class StatusController extends ManageStatusController
{
    protected $type = 'zunit_test_1';
    protected $typeModel = Zunit_test_1::class;
}