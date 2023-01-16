<?php

namespace App\Http\Controllers\Entities\Field;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Field;

class ManageController extends AbstractManageController
{
    protected $type = 'field';
    protected $typeModel = Field::class;
}