<?php

namespace App\Http\Controllers\Entities\Zunit_test_4;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Zunit_test_4;

class EditController extends AbstractCreateEditController
{
    protected $type = 'zunit_test_4';
    protected $data = Zunit_test_4::class;
    protected $action = "edit";

}