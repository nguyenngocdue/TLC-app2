<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibRoleSets;
use App\Utils\Support\Json\ReadOnlyWLProps;

class ManageVReadOnlyWLProps extends ManageV_Parent
{
    protected $routeKey = "_rol-wl";
    protected $jsonGetSet = ReadOnlyWLProps::class;

    protected function getColumnSource()
    {
        return LibRoleSets::getAll();
    }
}
