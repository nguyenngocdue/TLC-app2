<?php

namespace App\Http\Controllers\Render\Zunit_test_2s;

use App\Http\Controllers\Render\ViewAllController;
use App\Models\Zunit_test_2;

class Zunit_test_2sViewAllController extends ViewAllController
{
    protected $type = 'zunit_test_2';
    protected $typeModel = Zunit_test_2::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_2s',
        'edit' => 'read-zunit_test_2s|create-zunit_test_2s|edit-zunit_test_2s|edit-others-zunit_test_2s',
        'delete' => 'read-zunit_test_2s|create-zunit_test_2s|edit-zunit_test_2s|edit-others-zunit_test_2s|delete-zunit_test_2s|delete-others-zunit_test_2s'
    ];
}