<?php

namespace App\Http\Controllers\Entities\Zunit_test_4;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Zunit_test_4;

class ListenerController extends AbstractListenerController
{
    protected $type = 'zunit_test_4';
    protected $typeModel = Zunit_test_4::class;
}
