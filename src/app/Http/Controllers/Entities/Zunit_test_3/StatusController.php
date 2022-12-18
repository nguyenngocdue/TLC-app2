<?php

namespace App\Http\Controllers\Entities\Zunit_test_3;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Zunit_test_3;

class StatusController extends AbstractStatusController
{
    protected $type = 'zunit_test_3';
    protected $typeModel = Zunit_test_3::class;
}