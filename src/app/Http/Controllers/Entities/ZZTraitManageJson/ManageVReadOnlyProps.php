<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\Json\ReadOnlyProps;

class ManageVReadOnlyProps extends ManageV_Parent
{
    protected $routeKey = "_rol";
    protected $jsonGetSet = ReadOnlyProps::class;
}
