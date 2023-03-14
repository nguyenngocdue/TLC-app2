<?php

namespace App\Http\Controllers\Entities\Prod_discipline_2;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Prod_discipline_2;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'prod_discipline_2';
    protected $typeModel = Prod_discipline_2::class;
}