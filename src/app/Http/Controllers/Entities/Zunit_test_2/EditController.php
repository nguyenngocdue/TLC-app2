<?php

namespace App\Http\Controllers\Entities\Zunit_test_2;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Zunit_test_2;

class EditController extends AbstractCreateEditController
{
    protected $type = 'zunit_test_2';
    protected $data = Zunit_test_2::class;
    protected $action = "edit";

}