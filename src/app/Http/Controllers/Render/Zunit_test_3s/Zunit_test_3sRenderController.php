<?php

namespace App\Http\Controllers\Render\Zunit_test_3s;

use App\Http\Controllers\Render\RenderController;
use App\Models\Zunit_test_3;

class Zunit_test_3sRenderController extends RenderController
{
    protected $type = 'zunit_test_3s';
    protected $typeModel = Zunit_test_3::class;
    protected $permissionMiddleware = [
        'read' => 'read_zunit_test_3s|edit_zunit_test_3s|edit_other_zunit_test_3s|delete_zunit_test_3s|delete_other_zunit_test_3s',
        'edit' => 'edit_zunit_test_3s|edit_other_zunit_test_3s',
        'delete' => 'delete_zunit_test_3s|delete_other_zunit_test_3s'
    ];
}