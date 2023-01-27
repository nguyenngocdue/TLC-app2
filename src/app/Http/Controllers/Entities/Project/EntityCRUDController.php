<?php

namespace App\Http\Controllers\Entities\Project;

use App\Http\Controllers\Entities\AbstractEntityCRUDController;
use App\Models\Project;

class EntityCRUDController extends AbstractEntityCRUDController
{
    protected $type = 'project';
    protected $data = Project::class;
}