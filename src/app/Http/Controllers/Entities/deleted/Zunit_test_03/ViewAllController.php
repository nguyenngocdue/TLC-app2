<?php

namespace App\Http\Controllers\Entities\Zunit_test_03;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_03;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_03';
    protected $typeModel = Zunit_test_03::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_03s',
        'edit' => 'read-zunit_test_03s|create-zunit_test_03s|edit-zunit_test_03s|edit-others-zunit_test_03s',
        'delete' => 'read-zunit_test_03s|create-zunit_test_03s|edit-zunit_test_03s|edit-others-zunit_test_03s|delete-zunit_test_03s|delete-others-zunit_test_03s'
    ];
}