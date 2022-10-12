<?php

namespace App\Http\Controllers\Render\Zunit_test_3s;

use App\Http\Controllers\Render\RenderController;
use App\Models\Zunit_test_3;

class Zunit_test_3sRenderController extends RenderController
{
    protected $type = 'zunit_test_3';
    protected $typeModel = Zunit_test_3::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_3',
        'edit' => 'read-zunit_test_3|create-zunit_test_3|edit-zunit_test_3|edit-others-zunit_test_3',
        'delete' => 'read-zunit_test_3|create-zunit_test_3|edit-zunit_test_3|edit-others-zunit_test_3|delete-zunit_test_3|delete-others-zunit_test_3'
    ];
}