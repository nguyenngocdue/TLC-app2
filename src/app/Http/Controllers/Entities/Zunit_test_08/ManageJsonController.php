<?php

namespace App\Http\Controllers\Entities\Zunit_test_08;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Zunit_test_08;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'zunit_test_08';
    protected $typeModel = Zunit_test_08::class;
}