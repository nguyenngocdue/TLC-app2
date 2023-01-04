<?php

namespace App\Http\Controllers\Entities\Prod_sequence;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Prod_sequence;

class EditController extends AbstractCreateEditController
{
    protected $type = 'prod_sequence';
    protected $data = Prod_sequence::class;
    protected $action = "edit";

}