<?php

namespace App\Http\Controllers\Entities\Public_holiday;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Public_holiday;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'public_holiday';
    protected $typeModel = Public_holiday::class;
}