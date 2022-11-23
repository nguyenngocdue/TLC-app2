<?php

namespace App\Http\Controllers\Render\Wir_descriptions;

use App\Http\Controllers\Render\CreateEditController;
use App\Models\Wir_description;

class Wir_descriptionsCreateController extends CreateEditController
{
    protected $type = 'wir_description';
    protected $data = Wir_description::class;
    protected $action = "create";
}