<?php


namespace App\BigThink;

use Illuminate\Support\Str;

trait TraitMenuTitle
{
    function getMenuTitle()
    {
        return $this->menuTitle ?? Str::headline($this->getTable());
    }
}
