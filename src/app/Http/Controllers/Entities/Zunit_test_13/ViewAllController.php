<?php

namespace App\Http\Controllers\Entities\Zunit_test_13;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_13;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_13';
    protected $typeModel = Zunit_test_13::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_13s',
        'edit' => 'read-zunit_test_13s|create-zunit_test_13s|edit-zunit_test_13s|edit-others-zunit_test_13s',
        'delete' => 'read-zunit_test_13s|create-zunit_test_13s|edit-zunit_test_13s|edit-others-zunit_test_13s|delete-zunit_test_13s|delete-others-zunit_test_13s'
    ];
}