<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibRoleSets;
use App\Utils\Support\Json\VisibleWLProps;

class ManageVVisibleWLProps extends ManageV_Parent
{
    protected $routeKey = "_vsb-wl";
    protected $jsonGetSet = VisibleWLProps::class;
    protected $headerTop = 16;
    protected $showToggleColumn = false;
    protected $showToggleRow = false;

    protected function getColumnSource()
    {
        return LibRoleSets::getAll();
    }
}
