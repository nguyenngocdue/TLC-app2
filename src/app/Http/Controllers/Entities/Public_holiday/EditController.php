<?php

namespace App\Http\Controllers\Entities\Public_holiday;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Public_holiday;

class EditController extends AbstractCreateEditController
{
    protected $type = 'public_holiday';
    protected $data = Public_holiday::class;
    protected $action = "edit";

}