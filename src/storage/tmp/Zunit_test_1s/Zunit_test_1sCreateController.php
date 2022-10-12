<?php

namespace App\Http\Controllers\Render\Zunit_test_1s;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Zunit_test_1;

class Zunit_test_1sCreateController extends CreateEditController
{
    protected $type = 'zunit_test_1';
    protected $data = Zunit_test_1::class;
    protected $action = "create";
}