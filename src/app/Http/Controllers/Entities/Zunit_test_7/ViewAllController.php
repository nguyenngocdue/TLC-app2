<?php

namespace App\Http\Controllers\Entities\Zunit_test_7;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_7;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_7';
    protected $typeModel = Zunit_test_7::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_7s',
        'edit' => 'read-zunit_test_7s|create-zunit_test_7s|edit-zunit_test_7s|edit-others-zunit_test_7s',
        'delete' => 'read-zunit_test_7s|create-zunit_test_7s|edit-zunit_test_7s|edit-others-zunit_test_7s|delete-zunit_test_7s|delete-others-zunit_test_7s'
    ];
}