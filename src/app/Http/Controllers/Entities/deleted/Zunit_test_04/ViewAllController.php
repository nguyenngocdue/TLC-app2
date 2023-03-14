<?php

namespace App\Http\Controllers\Entities\Zunit_test_04;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_04;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_04';
    protected $typeModel = Zunit_test_04::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_04s',
        'edit' => 'read-zunit_test_04s|create-zunit_test_04s|edit-zunit_test_04s|edit-others-zunit_test_04s',
        'delete' => 'read-zunit_test_04s|create-zunit_test_04s|edit-zunit_test_04s|edit-others-zunit_test_04s|delete-zunit_test_04s|delete-others-zunit_test_04s'
    ];
}