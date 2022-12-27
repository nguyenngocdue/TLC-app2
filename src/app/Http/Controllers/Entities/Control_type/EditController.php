<?php

namespace App\Http\Controllers\Entities\Control_type;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Control_type;

class EditController extends AbstractCreateEditController
{
    protected $type = 'control_type';
    protected $data = Control_type::class;
    protected $action = "edit";

}