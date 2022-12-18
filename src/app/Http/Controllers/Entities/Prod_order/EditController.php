<?php

namespace App\Http\Controllers\Entities\Prod_order;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Prod_order;

class EditController extends AbstractCreateEditController
{
    protected $type = 'prod_order';
    protected $data = Prod_order::class;
    protected $action = "edit";

}