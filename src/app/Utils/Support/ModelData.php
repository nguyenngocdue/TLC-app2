<?php


namespace App\Utils\Support;

use Illuminate\Support\Str;

class ModelData
{
    public static function initModelByField($field)
    {
        $modelName = Str::singular(ucfirst(str_replace(['_id', '_name'], '', $field)));
        $modelClass = 'App\Models\\' . $modelName;
        if (class_exists($modelClass)) {
            try {
                return new $modelClass;
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            dd("Model class $modelClass does not exist.");
        }
        return null;
    }
}
