<?php

namespace App\Http\Controllers\Entities\Term;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Term;

class ListenerController extends AbstractListenerController
{
    protected $type = 'term';
    protected $typeModel = Term::class;
}
