<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\Json\VisibleProps;

class ManageVVisibleProps extends ManageV_Parent
{
    protected $routeKey = "_vsb";
    protected $jsonGetSet = VisibleProps::class;
}
