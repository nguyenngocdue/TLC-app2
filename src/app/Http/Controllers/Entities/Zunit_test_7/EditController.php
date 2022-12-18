<?php

namespace App\Http\Controllers\Entities\Zunit_test_7;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Zunit_test_7;

class EditController extends AbstractCreateEditController
{
    protected $type = 'zunit_test_7';
    protected $data = Zunit_test_7::class;
    protected $action = "edit";

}