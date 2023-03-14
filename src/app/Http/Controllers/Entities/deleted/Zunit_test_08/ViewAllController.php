<?php

namespace App\Http\Controllers\Entities\Zunit_test_08;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_08;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_08';
    protected $typeModel = Zunit_test_08::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_08s',
        'edit' => 'read-zunit_test_08s|create-zunit_test_08s|edit-zunit_test_08s|edit-others-zunit_test_08s',
        'delete' => 'read-zunit_test_08s|create-zunit_test_08s|edit-zunit_test_08s|edit-others-zunit_test_08s|delete-zunit_test_08s|delete-others-zunit_test_08s'
    ];
}