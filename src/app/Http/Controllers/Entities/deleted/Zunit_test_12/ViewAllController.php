<?php

namespace App\Http\Controllers\Entities\Zunit_test_12;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_12;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_12';
    protected $typeModel = Zunit_test_12::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_12s',
        'edit' => 'read-zunit_test_12s|create-zunit_test_12s|edit-zunit_test_12s|edit-others-zunit_test_12s',
        'delete' => 'read-zunit_test_12s|create-zunit_test_12s|edit-zunit_test_12s|edit-others-zunit_test_12s|delete-zunit_test_12s|delete-others-zunit_test_12s'
    ];
}