<?php

namespace App\Http\Controllers\Entities\Zunit_test_11;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_11;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_11';
    protected $typeModel = Zunit_test_11::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_11s',
        'edit' => 'read-zunit_test_11s|create-zunit_test_11s|edit-zunit_test_11s|edit-others-zunit_test_11s',
        'delete' => 'read-zunit_test_11s|create-zunit_test_11s|edit-zunit_test_11s|edit-others-zunit_test_11s|delete-zunit_test_11s|delete-others-zunit_test_11s'
    ];
}