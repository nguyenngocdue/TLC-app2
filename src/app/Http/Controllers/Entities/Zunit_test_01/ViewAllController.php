<?php

namespace App\Http\Controllers\Entities\Zunit_test_01;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_01;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_01';
    protected $typeModel = Zunit_test_01::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_01s',
        'edit' => 'read-zunit_test_01s|create-zunit_test_01s|edit-zunit_test_01s|edit-others-zunit_test_01s',
        'delete' => 'read-zunit_test_01s|create-zunit_test_01s|edit-zunit_test_01s|edit-others-zunit_test_01s|delete-zunit_test_01s|delete-others-zunit_test_01s'
    ];
}