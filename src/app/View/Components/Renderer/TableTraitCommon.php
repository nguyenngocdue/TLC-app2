<?php

namespace App\View\Components\Renderer;

trait TableTraitCommon
{
    private function getStyleStr($column)
    {
        $width = (isset($column['width']) && $column['width'] != '') ? $column['width'] : 100;
        return "style='width:" . $width . "px'";
    }
}
