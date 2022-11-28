<?php

namespace App\View\Components\Formula;

use App\Helpers\Helper;

class All_ConcatNameWith123
{
    public static function All_ConcatNameWith123($event)
    {
        $instanceDB = $event->dataEvent[0];
        $dataDB = $instanceDB->getAttributes();
        if (!isset($dataDB['name'])) return false;
        $props = $event->dataEvent[1];

        $colNamehasFormula = Helper::getColNamesbyConditions($props, 'formula', null, null, null, 'type2');
        // dd($colNamehasFormula);

        $text = "123";
        $valNameField = $dataDB['name'];

        $newDataInput = [];
        foreach ($colNamehasFormula as $value) {
            if ($value['formula'] === 'All_ConcatNameWith123') {
                $newDataInput[$value['column_name']] = $valNameField . $text;
            }
        }
        $newDataInput = array_replace($dataDB, $newDataInput);
        unset($newDataInput['created_at']);
        unset($newDataInput['updated_at']);
        $instanceDB->fill($newDataInput);
        $instanceDB->save();
    }
}
