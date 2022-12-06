<?php

namespace App\Http\Controllers\Render\Zunit_test_7s;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Zunit_test_7;

class Zunit_test_7sViewAllController extends ViewAllController
{
    protected $type = 'zunit_test_7';
    protected $typeModel = Zunit_test_7::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_7s',
        'edit' => 'read-zunit_test_7s|create-zunit_test_7s|edit-zunit_test_7s|edit-others-zunit_test_7s',
        'delete' => 'read-zunit_test_7s|create-zunit_test_7s|edit-zunit_test_7s|edit-others-zunit_test_7s|delete-zunit_test_7s|delete-others-zunit_test_7s'
    ];
}
