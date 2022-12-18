<?php

namespace App\Http\Controllers\Entities\Zunit_test_3;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Zunit_test_3;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'zunit_test_3';
    protected $data = Zunit_test_3::class;
    protected $action = "create";
}