<?php

namespace App\Http\Controllers\Entities\Zunit_test_3;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Zunit_test_3;

class PropController extends AbstractPropController
{
    protected $type = 'zunit_test_3';
    protected $typeModel = Zunit_test_3::class;
}
