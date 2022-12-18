<?php

namespace App\Http\Controllers\Entities\Zunit_test_1;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_1;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_1';
    protected $typeModel = Zunit_test_1::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_1s',
        'edit' => 'read-zunit_test_1s|create-zunit_test_1s|edit-zunit_test_1s|edit-others-zunit_test_1s',
        'delete' => 'read-zunit_test_1s|create-zunit_test_1s|edit-zunit_test_1s|edit-others-zunit_test_1s|delete-zunit_test_1s|delete-others-zunit_test_1s'
    ];
}