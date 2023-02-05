<?php

namespace App\Http\Controllers\Entities\Zunit_test_06;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_06;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_06';
    protected $typeModel = Zunit_test_06::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_06s',
        'edit' => 'read-zunit_test_06s|create-zunit_test_06s|edit-zunit_test_06s|edit-others-zunit_test_06s',
        'delete' => 'read-zunit_test_06s|create-zunit_test_06s|edit-zunit_test_06s|edit-others-zunit_test_06s|delete-zunit_test_06s|delete-others-zunit_test_06s'
    ];
}