<?php

namespace App\View\Components\Renderer\Table;

trait TableTraitCommon
{
    // private function getStyleStr($column)
    // {
    //     $width = (isset($column['width']) && $column['width'] != '') ? $column['width'] : 100;
    //     return "style='width:" . $width . "px'";
    // }

    private function getStyleStr($params)
    {
        $result = [];
        foreach ($params as $key => $value) {
            $result[] = "$key:$value";
        }
        return "style='" . join(";", $result) . "'";
    }

    private function isInvisible($column)
    {
        return (isset($column['invisible']) && $column['invisible'] == true);
    }

    private function getFixedLeftOrRight($column, $index, $leftOrRight, $thOrTd)
    {
        $result = "";
        if (isset($column['fixed'])) {
            switch ($column['fixed']) {
                case "left":
                    $result = "table-{$thOrTd}-fixed-left table-{$thOrTd}-fixed-left-$index";
                    break;
                case "left-no-bg":
                    $result = "table-{$thOrTd}-fixed-left-no-bg table-{$thOrTd}-fixed-left-$index";
                    break;
                case "right":
                    $result = "table-{$thOrTd}-fixed-right table-{$thOrTd}-fixed-right-$index";
                    break;
                case "right-no-bg":
                    $result = "table-{$thOrTd}-fixed-right-no-bg table-{$thOrTd}-fixed-right-$index";
                    break;
            }
        }
        return $result;
    }
}
