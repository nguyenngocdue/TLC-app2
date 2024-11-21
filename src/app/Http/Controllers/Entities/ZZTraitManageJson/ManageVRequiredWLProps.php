<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibRoleSets;
use App\Utils\Support\Json\RequiredWLProps;

class ManageVRequiredWLProps extends ManageV_Parent
{
    protected $routeKey = "_rqr-wl";
    protected $jsonGetSet = RequiredWLProps::class;

    protected function getColumnSource()
    {
        return LibRoleSets::getAll();
    }
}
