<?php

namespace App\Http\Controllers\Entities\Zunit_test_07;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_07;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_07';
    protected $typeModel = Zunit_test_07::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_07s',
        'edit' => 'read-zunit_test_07s|create-zunit_test_07s|edit-zunit_test_07s|edit-others-zunit_test_07s',
        'delete' => 'read-zunit_test_07s|create-zunit_test_07s|edit-zunit_test_07s|edit-others-zunit_test_07s|delete-zunit_test_07s|delete-others-zunit_test_07s'
    ];
}