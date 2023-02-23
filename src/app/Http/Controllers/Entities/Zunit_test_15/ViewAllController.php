<?php

namespace App\Http\Controllers\Entities\Zunit_test_15;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_15;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_15';
    protected $typeModel = Zunit_test_15::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_15s',
        'edit' => 'read-zunit_test_15s|create-zunit_test_15s|edit-zunit_test_15s|edit-others-zunit_test_15s',
        'delete' => 'read-zunit_test_15s|create-zunit_test_15s|edit-zunit_test_15s|edit-others-zunit_test_15s|delete-zunit_test_15s|delete-others-zunit_test_15s'
    ];
}