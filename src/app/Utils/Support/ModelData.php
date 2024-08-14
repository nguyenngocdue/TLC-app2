<?php


namespace App\Utils\Support;

use Illuminate\Support\Str;

class ModelData
{
    public static function initModelByField($field)
    {
        $modelName = Str::singular(ucfirst($field));
        $modelClass = 'App\Models\\' . $modelName;
        if (class_exists($modelClass)) {
            try {
                return new $modelClass;
            } catch (\Exception $e) {
                dd($e->getMessage());
            }
        } else {
            dump("Model class $modelClass does not exist.");
        }
        return null;
    }
}
