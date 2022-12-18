<?php

namespace App\Http\Controllers\Entities\Zunit_test_3;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_3;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_3';
    protected $typeModel = Zunit_test_3::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_3s',
        'edit' => 'read-zunit_test_3s|create-zunit_test_3s|edit-zunit_test_3s|edit-others-zunit_test_3s',
        'delete' => 'read-zunit_test_3s|create-zunit_test_3s|edit-zunit_test_3s|edit-others-zunit_test_3s|delete-zunit_test_3s|delete-others-zunit_test_3s'
    ];
}