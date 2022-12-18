<?php

namespace App\Http\Controllers\Entities\Prod_routing;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Prod_routing;

class EditController extends AbstractCreateEditController
{
    protected $type = 'prod_routing';
    protected $data = Prod_routing::class;
    protected $action = "edit";

}