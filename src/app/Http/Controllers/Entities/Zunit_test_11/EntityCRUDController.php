<?php

namespace App\Http\Controllers\Entities\Zunit_test_11;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Zunit_test_11;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'zunit_test_11';
    protected $data = Zunit_test_11::class;
}