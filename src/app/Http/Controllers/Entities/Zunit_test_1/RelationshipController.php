<?php

namespace App\Http\Controllers\Entities\Zunit_test_1;

use App\Http\Controllers\Entities\AbstractRelationshipController;
use App\Models\Zunit_test_1;

class RelationshipController extends AbstractRelationshipController
{
    protected $type = 'zunit_test_1';
    protected $typeModel = Zunit_test_1::class;
}
