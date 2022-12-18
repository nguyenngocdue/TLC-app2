<?php

namespace App\Http\Controllers\Entities\Workplace;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Workplace;

class EditController extends AbstractCreateEditController
{
    protected $type = 'workplace';
    protected $data = Workplace::class;
    protected $action = "edit";

}