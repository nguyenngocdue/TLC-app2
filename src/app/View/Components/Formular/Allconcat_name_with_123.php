<?php

namespace App\View\Components\Formular;

use App\Helpers\Helper;

class Allconcat_name_with_123
{
    public static function Allconcat_name_with_123($event)
    {
        $instanceDB = $event->dataEvent[0];
        $dataDB = $instanceDB->getAttributes();
        $props = $event->dataEvent[1];


        $colNamehasFormular = Helper::getColNamesbyCondition($props, 'formular', null, null, null, 'type2');
        // dd($colNamehasFormular);

        $text = "123";
        $valNameField = $dataDB['name'];

        $newDataInput = [];
        foreach ($colNamehasFormular as $value) {
            if ($value['formular'] === 'all-concat_name_with_123') {
                $formularName = $valNameField . $text;
                $newDataInput[$value['column_name']] = $formularName;
            }
        }
        $newDataInput = array_replace($dataDB, $newDataInput);
        unset($newDataInput['created_at']);
        unset($newDataInput['updated_at']);
        $instanceDB->fill($newDataInput);
        $instanceDB->save();
    }
}
