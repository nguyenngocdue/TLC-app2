<?php

namespace App\Http\Controllers\Render\Zunit_test_3s;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Zunit_test_3;

class Zunit_test_3sViewAllController extends ViewAllController
{
    protected $type = 'zunit_test_3';
    protected $typeModel = Zunit_test_3::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_3s',
        'edit' => 'read-zunit_test_3s|create-zunit_test_3s|edit-zunit_test_3s|edit-others-zunit_test_3s',
        'delete' => 'read-zunit_test_3s|create-zunit_test_3s|edit-zunit_test_3s|edit-others-zunit_test_3s|delete-zunit_test_3s|delete-others-zunit_test_3s'
    ];
}
