<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibRoleSets;
use App\Utils\Support\Json\HiddenExcProps;

class ManageVHiddenExcProps extends ManageV_Parent
{
    protected $routeKey = "_hdn-exc";
    protected $jsonGetSet = HiddenExcProps::class;
    protected $headerTop = 16;

    protected function getColumnSource()
    {
        return LibRoleSets::getAll();
    }
}
