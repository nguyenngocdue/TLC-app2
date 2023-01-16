<?php

namespace App\Http\Controllers\Entities\Wir_description;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Wir_description;

class ManageController extends AbstractManageController
{
    protected $type = 'wir_description';
    protected $typeModel = Wir_description::class;
}