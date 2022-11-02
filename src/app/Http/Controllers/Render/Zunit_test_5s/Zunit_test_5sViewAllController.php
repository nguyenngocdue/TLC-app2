<?php

namespace App\Http\Controllers\Render\Zunit_test_5s;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Zunit_test_5;

class Zunit_test_5sViewAllController extends ViewAllController
{
    protected $type = 'zunit_test_5';
    protected $typeModel = Zunit_test_5::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_5s',
        'edit' => 'read-zunit_test_5s|create-zunit_test_5s|edit-zunit_test_5s|edit-others-zunit_test_5s',
        'delete' => 'read-zunit_test_5s|create-zunit_test_5s|edit-zunit_test_5s|edit-others-zunit_test_5s|delete-zunit_test_5s|delete-others-zunit_test_5s'
    ];
}
