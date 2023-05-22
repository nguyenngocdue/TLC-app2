<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Utils\TraitManagePropColumns;

class ManageStandardPropsController extends AbstractManageLibController
{
    use TraitManagePropColumns;
    protected $title = "Manage Standard Props";
    protected $libraryClass = LibStandardProps::class;
    protected $route = "manageStandardProps";
}
