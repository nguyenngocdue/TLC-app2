<?php

namespace App\Utils\Support;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;

class Entities
{
    private static $singleton = null;

    public static function getAll()
    {
        if (!static::$singleton) {
            static::$singleton = static::getAllExpensive();
        }
        return static::$singleton;
    }

    private static function getAllExpensive()
    {
        $entities = [];
        $modelsPath = app_path('Models');
        $modelFiles = File::allFiles($modelsPath);
        foreach ($modelFiles as $modelFile) {
            $entities[] = 'App\\Models\\' . $modelFile->getFilenameWithoutExtension();
        }
        $array = [];
        foreach ($entities as $entity) {
            $value = in_array('App\Utils\PermissionTraits\CheckPermissionEntities', class_uses_recursive($entity));
            if ($value) {
                $model = App::make($entity);
                array_push($array, $model);
            }
        }
        return $array;
    }
}
