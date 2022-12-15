<?php

namespace App\Http\Controllers\Manage\Zunit_test_2;

use App\Http\Controllers\Manage\ManageStatusController;
use App\Models\Zunit_test_2;

class StatusController extends ManageStatusController
{
    protected $type = 'zunit_test_2';
    protected $typeModel = Zunit_test_2::class;
}