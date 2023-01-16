<?php

namespace App\Http\Controllers\Entities\Term;

use App\Http\Controllers\Entities\AbstractManageController;
use App\Models\Term;

class ManageController extends AbstractManageController
{
    protected $type = 'term';
    protected $typeModel = Term::class;
}