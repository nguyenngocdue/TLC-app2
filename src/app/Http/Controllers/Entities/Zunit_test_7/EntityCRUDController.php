<?php

namespace App\Http\Controllers\Entities\Zunit_test_7;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Zunit_test_7;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'zunit_test_7';
    protected $data = Zunit_test_7::class;
}