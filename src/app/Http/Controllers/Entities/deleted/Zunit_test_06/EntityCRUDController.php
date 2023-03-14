<?php

namespace App\Http\Controllers\Entities\Zunit_test_06;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Zunit_test_06;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'zunit_test_06';
    protected $data = Zunit_test_06::class;
}