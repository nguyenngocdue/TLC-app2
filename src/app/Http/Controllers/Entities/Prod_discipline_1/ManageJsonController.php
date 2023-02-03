<?php

namespace App\Http\Controllers\Entities\Prod_discipline_1;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Prod_discipline_1;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'prod_discipline_1';
    protected $typeModel = Prod_discipline_1::class;
}