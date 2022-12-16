<?php

namespace App\Http\Controllers\Manage\Zunit_test_4;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Zunit_test_4;

class RelationshipController extends ManageRelationshipController
{
    protected $type = 'zunit_test_4';
    protected $typeModel = Zunit_test_4::class;
}
