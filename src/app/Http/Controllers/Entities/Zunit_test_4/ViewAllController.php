<?php

namespace App\Http\Controllers\Entities\Zunit_test_4;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_4;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_4';
    protected $typeModel = Zunit_test_4::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_4s',
        'edit' => 'read-zunit_test_4s|create-zunit_test_4s|edit-zunit_test_4s|edit-others-zunit_test_4s',
        'delete' => 'read-zunit_test_4s|create-zunit_test_4s|edit-zunit_test_4s|edit-others-zunit_test_4s|delete-zunit_test_4s|delete-others-zunit_test_4s'
    ];
}