<?php

namespace App\Http\Controllers\Entities\Wir_description;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Wir_description;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'wir_description';
    protected $typeModel = Wir_description::class;
}