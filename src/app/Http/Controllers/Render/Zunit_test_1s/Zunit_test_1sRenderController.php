<?php

namespace App\Http\Controllers\Render\Zunit_test_1s;

use App\Http\Controllers\Render\RenderController;
use App\Models\Zunit_test_1;

class Zunit_test_1sRenderController extends RenderController
{
    protected $type = 'zunit_test_1';
    protected $typeModel = Zunit_test_1::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_1',
        'edit' => 'read-zunit_test_1|create-zunit_test_1|edit-zunit_test_1|edit-others-zunit_test_1',
        'delete' => 'read-zunit_test_1|create-zunit_test_1|edit-zunit_test_1|edit-others-zunit_test_1|delete-zunit_test_1|delete-others-zunit_test_1'
    ];
}
