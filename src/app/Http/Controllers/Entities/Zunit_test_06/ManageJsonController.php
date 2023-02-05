<?php

namespace App\Http\Controllers\Entities\Zunit_test_06;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Zunit_test_06;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'zunit_test_06';
    protected $typeModel = Zunit_test_06::class;
}