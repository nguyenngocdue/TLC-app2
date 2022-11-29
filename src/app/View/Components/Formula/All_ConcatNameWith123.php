<?php

namespace App\View\Components\Formula;

use App\Helpers\Helper;

class All_ConcatNameWith123
{
    public static function All_ConcatNameWith123($colNameHasFormula,  $concatStr,  $itemDB)
    {
        $newDataInput = [];
        foreach ($colNameHasFormula as $value) {
            $newDataInput[$value['column_name']] = $itemDB['name'] . $concatStr;
        }
        // dd($newDataInput);
        return $newDataInput;
    }
}
