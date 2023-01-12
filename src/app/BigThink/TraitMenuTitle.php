<?php


namespace App\BigThink;

use App\Http\Controllers\Workflow\LibApps;

trait TraitMenuTitle
{
    function getMenuTitle()
    {
        return LibApps::getFor($this->getTable())['title'];
    }
}
