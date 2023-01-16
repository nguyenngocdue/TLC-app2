<?php

namespace App\Http\Controllers\Entities\Workplace;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Workplace;

class ManageController extends AbstractManageController
{
    protected $type = 'workplace';
    protected $typeModel = Workplace::class;
}