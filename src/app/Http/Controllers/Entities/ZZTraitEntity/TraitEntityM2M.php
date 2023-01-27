<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

use App\Helpers\Helper;
use App\Utils\Support\Json\Relationships;

trait TraitEntityM2M
{
    private function getRelationships()
    {
        $instance = new $this->data;
        $eloquentParams = $instance->eloquentParams;
        $relationships = Relationships::getAllOf($this->type);
        return [$relationships, $eloquentParams];
    }

    private function syncManyToManyRelationship($data = null, $dataInput = []) //checkBox
    {
        [$relationships, $eloquentParams] = $this->getRelationships();
        dd($data, $dataInput, $relationships, $eloquentParams);

        if ($eloquentParams) {
            $itemIds = [];
            foreach ($eloquentParams as $fn => $value) {
                if ($value[0] === "belongsToMany") {
                    $colNamePivots = "";
                    foreach ($relationships as $relationship) {
                        if ($relationship['control_name'] === $fn) {
                            $colNamePivots = $dataInput[$relationship['control_name']] ?? [];
                            $data->{$fn}()->sync($colNamePivots);
                            break;
                        }
                    }
                }
            }
            return $itemIds;
        }
    }


    private function syncManyToManyToDB($data, $dataInput)
    {
        $allFields = Helper::getDataDbByName('fields', 'name', 'id');

        $newDataInputIsArray = array_filter($dataInput, fn ($item) => is_array($item) && gettype(array_values($item)[0]) !== 'object');
        $array = $newDataInputIsArray;

        foreach ($newDataInputIsArray as $key => $value) {
            $keyColName = str_replace('delAll', '', $key);
            $hasCheck = isset($newDataInputIsArray[$keyColName]);
            if ($hasCheck) {
                unset($array[$keyColName . 'delAll']);
            }
        }

        //** Censored */
        foreach ($array as $key => $value) {
            $valueInt = array_map(fn ($i) => $i * 1, $value);
            $keyColName = str_replace('delAll', '', $key);

            if (isset($array[$keyColName]) || !str_contains($key, 'delAll')) {
                $termModelPath = $data->oracyParams[$key][1];
                $fieldName = str_replace('()', '', $key);
                $data->syncCheck($allFields[$fieldName], $termModelPath, $valueInt);
            } else {
                $termModelPath = $data->oracyParams[$keyColName][1];
                $fieldName = str_replace('()', '', $keyColName);
                $data->detachCheck($allFields[$fieldName], $termModelPath, $valueInt);
            }
        }
    }

    private function getManyToManyRelationship($currentElement) //checkBox
    {
        [$relationships, $eloquentParams] = $this->getRelationships();

        if ($eloquentParams) {
            $itemIds = [];
            foreach ($eloquentParams as $fn => $value) {
                if ($value[0] === "belongsToMany") {
                    foreach ($relationships as $relationship) {
                        if ($relationship['control_name'] === $fn) {
                            $itemIds[$relationship['control_name']] = json_decode($currentElement->$fn->pluck('id'));
                        }
                    }
                }
            }
            return $itemIds;
        }
    }
}
