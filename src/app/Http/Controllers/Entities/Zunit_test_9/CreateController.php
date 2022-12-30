<?php

namespace App\Http\Controllers\Entities\Zunit_test_9;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Zunit_test_9;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'zunit_test_9';
    protected $data = Zunit_test_9::class;
    protected $action = "create";
}