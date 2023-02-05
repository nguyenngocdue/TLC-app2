<?php

namespace App\Http\Controllers\Entities\Zunit_test_10;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_10;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_10';
    protected $typeModel = Zunit_test_10::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_10s',
        'edit' => 'read-zunit_test_10s|create-zunit_test_10s|edit-zunit_test_10s|edit-others-zunit_test_10s',
        'delete' => 'read-zunit_test_10s|create-zunit_test_10s|edit-zunit_test_10s|edit-others-zunit_test_10s|delete-zunit_test_10s|delete-others-zunit_test_10s'
    ];
}