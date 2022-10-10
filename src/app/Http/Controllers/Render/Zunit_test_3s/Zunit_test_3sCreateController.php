<?php

namespace App\Http\Controllers\Render\Zunit_test_3s;

use App\Http\Controllers\Render\EditController;
use App\Models\Zunit_test_3;

class Zunit_test_3sCreateController extends EditController
{
    protected $type = 'zunit_test_3';
    protected $data = Zunit_test_3::class;
    protected $action = "create";
}