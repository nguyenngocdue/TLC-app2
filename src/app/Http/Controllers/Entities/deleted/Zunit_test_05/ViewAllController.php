<?php

namespace App\Http\Controllers\Entities\Zunit_test_05;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_05;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_05';
    protected $typeModel = Zunit_test_05::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_05s',
        'edit' => 'read-zunit_test_05s|create-zunit_test_05s|edit-zunit_test_05s|edit-others-zunit_test_05s',
        'delete' => 'read-zunit_test_05s|create-zunit_test_05s|edit-zunit_test_05s|edit-others-zunit_test_05s|delete-zunit_test_05s|delete-others-zunit_test_05s'
    ];
}