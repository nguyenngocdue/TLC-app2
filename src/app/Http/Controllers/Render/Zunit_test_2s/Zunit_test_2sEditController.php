<?php

namespace App\Http\Controllers\Render\Zunit_test_2s;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Zunit_test_2;

class Zunit_test_2sEditController extends CreateEditController
{
    protected $type = 'zunit_test_2';
    protected $data = Zunit_test_2::class;
    protected $action = "edit";

}