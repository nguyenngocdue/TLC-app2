<?php

namespace App\Http\Controllers\Entities\Zunit_test_5;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Zunit_test_5;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'zunit_test_5';
    protected $data = Zunit_test_5::class;
    protected $action = "create";
}