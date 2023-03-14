<?php

namespace App\Http\Controllers\Entities\Zunit_test_09;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_09;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_09';
    protected $typeModel = Zunit_test_09::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_09s',
        'edit' => 'read-zunit_test_09s|create-zunit_test_09s|edit-zunit_test_09s|edit-others-zunit_test_09s',
        'delete' => 'read-zunit_test_09s|create-zunit_test_09s|edit-zunit_test_09s|edit-others-zunit_test_09s|delete-zunit_test_09s|delete-others-zunit_test_09s'
    ];
}