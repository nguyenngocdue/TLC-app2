<?php

namespace App\Http\Controllers\Entities\Field;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Field;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'field';
    protected $typeModel = Field::class;
}