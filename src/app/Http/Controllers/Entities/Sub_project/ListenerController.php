<?php

namespace App\Http\Controllers\Entities\Sub_project;

use App\Http\Controllers\Entities\AbstractListenerController;
use App\Models\Sub_project;

class ListenerController extends AbstractListenerController
{
    protected $type = 'sub_project';
    protected $typeModel = Sub_project::class;
}
