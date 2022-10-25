<?php

namespace App\Http\Controllers\Render\Zunit_test_5s;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Zunit_test_5;

class Zunit_test_5sCreateController extends CreateEditController
{
    protected $type = 'zunit_test_5';
    protected $data = Zunit_test_5::class;
    protected $action = "create";
}