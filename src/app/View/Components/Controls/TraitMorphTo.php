<?php

namespace App\View\Components\Controls;

use App\Utils\Support\Entities;
use App\Utils\Support\Json\Relationships;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

trait TraitMorphTo
{
    private function getParentTypeFromParentId($parent_id_name)
    {
        // $result = [];
        $relationships = Relationships::getAllOf($this->type);
        $elq = Str::modelPathFrom($this->type)::$eloquentParams;

        foreach ($relationships as $relationship) {
            if ($relationship['relationship'] === 'morphTo') {
                $control_name = $relationship['control_name'];
                $elqParams = $elq[$control_name];
                if ($parent_id_name == $elqParams[3]) return $elqParams[2];
            }
        }
        dump("NOT FOUND parent_id_name $parent_id_name");
    }

    private function getAllTypeMorphMany()
    {
        $allEntities = Entities::getAllSingularNames();
        $myModelPath = Str::modelPathFrom($this->type);
        $result = [];
        foreach ($allEntities as $entity) {
            $modelPath = Str::modelPathFrom($entity);
            if (class_exists($modelPath)) {
                foreach ($modelPath::$eloquentParams as $key => $params) {
                    if ($params[0] === 'morphMany' && $params[1] == $myModelPath) {
                        $result[$entity] = [
                            'id' => $modelPath,
                            'name' => Str::appTitle($entity),
                            'fn' => $key,
                        ];
                    }
                }
            } else {
                dump("Class $modelPath not found!");
            }
        }
        $result = array_values($result);
        // dump($result);
        return $result;
    }

    private function getAllIdMorphMany($attr_name)
    {
        $types = $this->getAllTypeMorphMany();
        $result = [];
        foreach ($types as $type) {
            $modelPath = $type['id'];
            $all = $modelPath::all();
            foreach ($all as $item)
                $result[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    $attr_name => $modelPath,
                ];
        }
        return $result;
    }
}
