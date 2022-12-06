<?php

namespace App\Http\Controllers\Render\Zunit_test_7s;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Zunit_test_7;

class Zunit_test_7sEditController extends CreateEditController
{
    protected $type = 'zunit_test_7';
    protected $data = Zunit_test_7::class;
    protected $action = "edit";

}