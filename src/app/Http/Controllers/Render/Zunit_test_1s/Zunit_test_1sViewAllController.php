<?php

namespace App\Http\Controllers\Render\Zunit_test_1s;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Zunit_test_1;

class Zunit_test_1sViewAllController extends ViewAllController
{
    protected $type = 'zunit_test_1s';
    protected $typeModel = Zunit_test_1::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_1s',
        'edit' => 'read-zunit_test_1s|create-zunit_test_1s|edit-zunit_test_1s|edit-others-zunit_test_1s',
        'delete' => 'read-zunit_test_1s|create-zunit_test_1s|edit-zunit_test_1s|edit-others-zunit_test_1s|delete-zunit_test_1s|delete-others-zunit_test_1s'
    ];
}
