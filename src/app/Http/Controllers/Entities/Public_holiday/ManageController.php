<?php

namespace App\Http\Controllers\Entities\Public_holiday;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Public_holiday;

class ManageController extends AbstractManageController
{
    protected $type = 'public_holiday';
    protected $typeModel = Public_holiday::class;
}