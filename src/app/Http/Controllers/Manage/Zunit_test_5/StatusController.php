<?php

namespace App\Http\Controllers\Manage\Zunit_test_5;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Zunit_test_5;

class StatusController extends ManageStatusController
{
    protected $type = 'zunit_test_5';
    protected $typeModel = Zunit_test_5::class;
}