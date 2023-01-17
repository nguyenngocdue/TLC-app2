<?php

namespace App\Http\Controllers\Entities\Zunit_test_4;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Zunit_test_4;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'zunit_test_4';
    protected $data = Zunit_test_4::class;
}