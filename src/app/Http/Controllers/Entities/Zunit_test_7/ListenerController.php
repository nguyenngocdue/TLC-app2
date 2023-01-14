<?php

namespace App\Http\Controllers\Entities\Zunit_test_7;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Zunit_test_7;

class ListenerController extends AbstractListenerController
{
    protected $type = 'zunit_test_7';
    protected $typeModel = Zunit_test_7::class;
}
