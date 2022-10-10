<?php

namespace App\Http\Controllers\Render\Zunit_test_3s;

use App\Http\Controllers\Render\EditController;
use App\Models\Zunit_test_3;

class Zunit_test_3sEditController extends EditController
{
    protected $type = 'zunit_test_3s';
    protected $data = Zunit_test_3::class;
}