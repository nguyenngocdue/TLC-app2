<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\ReadOnlyProps;

class ManageVReadOnlyProps extends ManageV_Parent
{
    protected $routeKey = "_rol";
    protected $jsonGetSet = ReadOnlyProps::class;

    protected function getColumnSource()
    {
        return LibStatuses::getFor($this->type);
    }
}
