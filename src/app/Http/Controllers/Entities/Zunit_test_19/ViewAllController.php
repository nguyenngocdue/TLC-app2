<?php

namespace App\Http\Controllers\Entities\Zunit_test_19;

use App\Http\Controllers\Entities\AbstractViewAllController;
use App\Models\Zunit_test_19;

class ViewAllController extends AbstractViewAllController
{
    protected $type = 'zunit_test_19';
    protected $typeModel = Zunit_test_19::class;
    protected $permissionMiddleware = [
        'read' => 'read-zunit_test_19s',
        'edit' => 'read-zunit_test_19s|create-zunit_test_19s|edit-zunit_test_19s|edit-others-zunit_test_19s',
        'delete' => 'read-zunit_test_19s|create-zunit_test_19s|edit-zunit_test_19s|edit-others-zunit_test_19s|delete-zunit_test_19s|delete-others-zunit_test_19s'
    ];
}