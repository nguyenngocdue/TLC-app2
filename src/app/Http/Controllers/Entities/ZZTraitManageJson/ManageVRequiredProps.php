<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\Json\RequiredProps;

class ManageVRequiredProps extends ManageV_Parent
{
    protected $routeKey = "_rqr";
    protected $jsonGetSet = RequiredProps::class;
}
