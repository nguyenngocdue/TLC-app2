<?php

namespace App\Http\Controllers\Entities\Zunit_test_5;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Zunit_test_5;

class ManageController extends AbstractManageController
{
    protected $type = 'zunit_test_5';
    protected $typeModel = Zunit_test_5::class;
}