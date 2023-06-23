<?php

namespace App\View\Components\Renderer\Table;

trait TableTraitCommon
{
    private function getStyleStr($column)
    {
        $width = (isset($column['width']) && $column['width'] != '') ? $column['width'] : 100;
        return "style='width:" . $width . "px'";
    }

    private function isInvisible($column)
    {
        return (isset($column['invisible']) && $column['invisible'] == true);
    }
}
