<?php

namespace App\Http\Controllers\Entities\Zunit_test_9;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_9;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_9';
    protected $typeModel = Zunit_test_9::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_9s',
        'edit' => 'read-zunit_test_9s|create-zunit_test_9s|edit-zunit_test_9s|edit-others-zunit_test_9s',
        'delete' => 'read-zunit_test_9s|create-zunit_test_9s|edit-zunit_test_9s|edit-others-zunit_test_9s|delete-zunit_test_9s|delete-others-zunit_test_9s'
    ];
}