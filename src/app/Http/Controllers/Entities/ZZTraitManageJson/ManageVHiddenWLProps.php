<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibRoleSets;
use App\Utils\Support\Json\HiddenWLProps;

class ManageVHiddenWLProps extends ManageV_Parent
{
    protected $routeKey = "_hdn-wl";
    protected $jsonGetSet = HiddenWLProps::class;
    protected $headerTop = 16;
    protected $showToggleColumn = false;
    protected $showToggleRow = false;

    protected function getColumnSource()
    {
        return LibRoleSets::getAll();
    }
}
