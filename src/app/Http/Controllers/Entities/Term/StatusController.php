<?php

namespace App\Http\Controllers\Entities\Term;

use App\Http\Controllers\Entities\AbstractStatusController;
use App\Models\Term;

class StatusController extends AbstractStatusController
{
    protected $type = 'term';
    protected $typeModel = Term::class;
}