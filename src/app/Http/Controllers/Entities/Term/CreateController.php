<?php

namespace App\Http\Controllers\Entities\Term;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Term;

class CreateController extends AbstractCreateEditController
{
    protected $type = 'term';
    protected $data = Term::class;
    protected $action = "create";
}