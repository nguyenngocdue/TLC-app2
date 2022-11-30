<?php

namespace App\View\Components\Formula;


class User_PositionRendered
{
    public function __invoke($arrayValues)
    {
        $concatText = $arrayValues[3] === "" ? "" : "($arrayValues[3])";
        $str = "$arrayValues[0] $arrayValues[1] $arrayValues[2] $concatText";
        return $str;
    }
}
