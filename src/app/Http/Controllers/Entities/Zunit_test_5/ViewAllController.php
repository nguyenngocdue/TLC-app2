<?php

namespace App\Http\Controllers\Entities\Zunit_test_5;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_5;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_5';
    protected $typeModel = Zunit_test_5::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_5s',
        'edit' => 'read-zunit_test_5s|create-zunit_test_5s|edit-zunit_test_5s|edit-others-zunit_test_5s',
        'delete' => 'read-zunit_test_5s|create-zunit_test_5s|edit-zunit_test_5s|edit-others-zunit_test_5s|delete-zunit_test_5s|delete-others-zunit_test_5s'
    ];
}