<?php

namespace App\Http\Controllers\Entities\Prod_sequence;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Prod_sequence;

class ManageController extends AbstractManageController
{
    protected $type = 'prod_sequence';
    protected $typeModel = Prod_sequence::class;
}