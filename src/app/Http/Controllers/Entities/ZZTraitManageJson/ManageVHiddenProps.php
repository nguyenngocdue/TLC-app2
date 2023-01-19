<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\Json\HiddenProps;

class ManageVHiddenProps extends ManageV_Parent
{
    protected $routeKey = "_hdn";
    protected $jsonGetSet = HiddenProps::class;
}
