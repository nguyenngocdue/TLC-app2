<?php

namespace App\Http\Controllers\Entities\Zunit_test_4;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Zunit_test_4;

class StatusController extends AbstractStatusController
{
    protected $type = 'zunit_test_4';
    protected $typeModel = Zunit_test_4::class;
}