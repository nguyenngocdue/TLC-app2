<?php

namespace App\Http\Controllers\Render\Zunit_test_4s;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Zunit_test_4;

class Zunit_test_4sEditController extends CreateEditController
{
    protected $type = 'zunit_test_4';
    protected $data = Zunit_test_4::class;
    protected $action = "edit";

}