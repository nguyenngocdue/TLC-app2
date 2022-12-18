<?php

namespace App\Http\Controllers\Entities\Zunit_test_2;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_2;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_2';
    protected $typeModel = Zunit_test_2::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_2s',
        'edit' => 'read-zunit_test_2s|create-zunit_test_2s|edit-zunit_test_2s|edit-others-zunit_test_2s',
        'delete' => 'read-zunit_test_2s|create-zunit_test_2s|edit-zunit_test_2s|edit-others-zunit_test_2s|delete-zunit_test_2s|delete-others-zunit_test_2s'
    ];
}