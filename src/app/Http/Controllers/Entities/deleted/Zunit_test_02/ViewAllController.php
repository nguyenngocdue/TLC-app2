<?php

namespace App\Http\Controllers\Entities\Zunit_test_02;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_02;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_02';
    protected $typeModel = Zunit_test_02::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_02s',
        'edit' => 'read-zunit_test_02s|create-zunit_test_02s|edit-zunit_test_02s|edit-others-zunit_test_02s',
        'delete' => 'read-zunit_test_02s|create-zunit_test_02s|edit-zunit_test_02s|edit-others-zunit_test_02s|delete-zunit_test_02s|delete-others-zunit_test_02s'
    ];
}