<?php

namespace App\Http\Controllers\Entities\Zunit_test_2;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Zunit_test_2;

class StatusController extends AbstractStatusController
{
    protected $type = 'zunit_test_2';
    protected $typeModel = Zunit_test_2::class;
}