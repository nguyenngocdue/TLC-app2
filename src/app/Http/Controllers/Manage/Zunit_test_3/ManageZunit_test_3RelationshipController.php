<?php

namespace App\Http\Controllers\Manage\Zunit_test_3;

use App\Http\Controllers\Manage\ManageRelationshipController;
use App\Models\Zunit_test_3;

class ManageZunit_test_3RelationshipController extends ManageRelationshipController
{
    protected $type = 'zunit_test_3';
    protected $typeModel = Zunit_test_3::class;
}
