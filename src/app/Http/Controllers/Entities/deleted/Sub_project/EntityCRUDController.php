<?php

namespace App\Http\Controllers\Entities\Sub_project;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Sub_project;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'sub_project';
    protected $data = Sub_project::class;
}