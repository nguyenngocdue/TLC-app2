<?php

namespace App\Http\Controllers\Entities\Zunit_test_1;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Zunit_test_1;

class EditController extends AbstractCreateEditController
{
    protected $type = 'zunit_test_1';
    protected $data = Zunit_test_1::class;
    protected $action = "edit";

}