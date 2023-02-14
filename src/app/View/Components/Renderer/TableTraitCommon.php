<?php

namespace App\View\Components\Renderer;

trait TableTraitCommon
{
    private function getStyleStr($column)
    {
        return (isset($column['width']) && $column['width'] != '') ? "style='width:" . $column['width'] . "px'" : "";
    }
}
