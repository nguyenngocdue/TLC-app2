<?php


namespace App\Utils\Support;

use Illuminate\Support\Str;

class ModelData
{
    public static function initModelByField($field)
    {
        $modelName = Str::singular(ucfirst(str_replace(['_id', '_name'], '', $field)));
        $ins = new ('App\Models\\' . $modelName);
        return $ins;
    }
}
