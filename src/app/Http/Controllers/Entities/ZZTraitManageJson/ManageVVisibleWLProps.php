<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibRoleSets;
use App\Utils\Support\Json\VisibleWLProps;

class ManageVVisibleWLProps extends ManageV_Parent
{
    protected $routeKey = "_vsb-wl";
    protected $jsonGetSet = VisibleWLProps::class;

    protected function getColumnSource()
    {
        return LibRoleSets::getAll();
    }
}
