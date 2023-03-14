<?php

namespace App\Http\Controllers\Entities\Term;

use App\Http\Controllers\Entities\AbstractManageJsonController;
use App\Models\Term;

class ManageJsonController extends AbstractManageJsonController
{
    protected $type = 'term';
    protected $typeModel = Term::class;
}