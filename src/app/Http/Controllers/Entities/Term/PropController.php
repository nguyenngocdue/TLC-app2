<?php

namespace App\Http\Controllers\Entities\Term;

use App\Http\Controllers\Entities\AbstractPropController;
use App\Models\Term;

class PropController extends AbstractPropController
{
    protected $type = 'term';
    protected $typeModel = Term::class;
}
