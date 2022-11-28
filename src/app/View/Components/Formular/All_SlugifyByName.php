<?php

namespace App\View\Components\Formular;

use App\Helpers\Helper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class All_SlugifyByName
{
    public static function All_SlugifyByName($event)
    {
        $instanceDB = $event->dataEvent[0];
        $dataDB = $instanceDB->getAttributes();
        if (!isset($dataDB['name']) && !isset($dataDB['slug'])) return false;

        $props = $event->dataEvent[1];
        $colNamehasFormular = Helper::getColNamesbyConditions($props, 'formular', null, null, null, 'type2');

        $newNameArray  = [];
        $dataDBbySlug = array_column(json_decode(DB::table($instanceDB->getTable())->where([['id', '!=', $dataDB['id']]])->select('id', 'slug')->get(), true),  'slug', 'id');
        foreach ($colNamehasFormular as $value) {
            if ($value['formular'] === 'All_SlugifyByName') {
                $content = is_null($dataDB['slug']) ? Str::slug($dataDB['name']) : Str::slug($dataDB['slug']);
                $newNameArray = Helper::slugNameToSaveDB($content, $dataDBbySlug);
            }
        }

        if (count($newNameArray)) {
            $newDataInput = array_replace($dataDB, $newNameArray);
            unset($newDataInput['created_at']);
            unset($newDataInput['updated_at']);
            $instanceDB->fill($newDataInput);
            $instanceDB->save();
        }
    }
}
