<?php

namespace App\View\Components\Formula;

class User_PositionRendered
{
    public function __invoke($position_prefix, $position_1, $position_2, $position_3)
    {
        $position_3 = $position_3 ? "($position_3)" : "";
        $str = "$position_prefix $position_1 $position_2 $position_3";
        $str = trim(str_replace("  ", " ", $str));
        return $str;
    }
}
