<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Utils\TraitManageDefaultValueColumns;

class ManageStandardDefaultValuesController extends AbstractManageLibController
{
    use TraitManageDefaultValueColumns;
    protected $title = "Manage Standard Default Values";
    protected $libraryClass = LibStandardDefaultValues::class;
    protected $route = "manageStandardDefaultValues";
}
