<?php

namespace App\Http\Controllers\Entities\Prod_routing_link;

use App\Http\Controllers\Entities\AbstractCreateEditController;
use App\Models\Prod_routing_link;

class EditController extends AbstractCreateEditController
{
    protected $type = 'prod_routing_link';
    protected $data = Prod_routing_link::class;
    protected $action = "edit";

}