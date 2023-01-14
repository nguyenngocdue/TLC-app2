<?php

namespace App\Http\Controllers\Entities\Project;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Project;

class ListenerController extends AbstractListenerController
{
    protected $type = 'project';
    protected $typeModel = Project::class;
}
