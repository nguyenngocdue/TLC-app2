<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\Json\RequiredProps;

class ManageVRequiredProps extends ManageV_Parent
{
    protected $routeKey = "_rqr";
    protected $jsonGetSet = RequiredProps::class;
    protected $headerTop = 9;

    protected function getColumnSource()
    {
        return LibStatuses::getFor($this->type);
    }
}
