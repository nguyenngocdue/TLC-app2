<?php

namespace App\Http\Controllers\Render\Zunit_test_4s;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Zunit_test_4;

class Zunit_test_4sViewAllController extends ViewAllController
{
    protected $type = 'zunit_test_4';
    protected $typeModel = Zunit_test_4::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_4s',
        'edit' => 'read-zunit_test_4s|create-zunit_test_4s|edit-zunit_test_4s|edit-others-zunit_test_4s',
        'delete' => 'read-zunit_test_4s|create-zunit_test_4s|edit-zunit_test_4s|edit-others-zunit_test_4s|delete-zunit_test_4s|delete-others-zunit_test_4s'
    ];
}