<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\VisibleProps;

class ManageVVisibleProps extends ManageV_Parent
{
    protected $routeKey = "_vsb";
    protected $jsonGetSet = VisibleProps::class;

    protected function getColumnSource()
    {
        return LibStatuses::getFor($this->type);
    }
}
