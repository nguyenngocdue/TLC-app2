<?php

namespace App\Http\Controllers\Entities\Prod_sequence;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Prod_sequence;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'prod_sequence';
    protected $typeModel = Prod_sequence::class;
}