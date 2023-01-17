<?php

namespace App\Http\Controllers\Entities\Prod_run;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Prod_run;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'prod_run';
    protected $data = Prod_run::class;
}