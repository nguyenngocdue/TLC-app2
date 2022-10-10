<?php

namespace App\Http\Controllers\Render\Zunit_test_1s;

use App\Http\Controllers\Render\RenderController;
use App\Models\Zunit_test_1;

class Zunit_test_1sRenderController extends RenderController
{
    protected $type = 'zunit_test_1s';
    protected $typeModel = Zunit_test_1::class;
    protected $permissionMiddleware = [
        'read' => 'read_zunit_test_1s|edit_zunit_test_1s|edit_other_zunit_test_1s|delete_zunit_test_1s|delete_other_zunit_test_1s',
        'edit' => 'edit_zunit_test_1s|edit_other_zunit_test_1s',
        'delete' => 'delete_zunit_test_1s|delete_other_zunit_test_1s'
    ];
}