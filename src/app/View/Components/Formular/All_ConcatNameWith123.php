<?php

namespace App\View\Components\Formular;

use App\Helpers\Helper;

class All_ConcatNameWith123
{
    public static function All_ConcatNameWith123($event)
    {
        $instanceDB = $event->dataEvent[0];
        $dataDB = $instanceDB->getAttributes();
        if (!isset($dataDB['name'])) return false;
        $props = $event->dataEvent[1];

        $colNamehasFormular = Helper::getColNamesbyConditions($props, 'formular', null, null, null, 'type2');
        // dd($colNamehasFormular);

        $text = "123";
        $valNameField = $dataDB['name'];

        $newDataInput = [];
        foreach ($colNamehasFormular as $value) {
            if ($value['formular'] === 'all-concat_name_with_123') {
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
