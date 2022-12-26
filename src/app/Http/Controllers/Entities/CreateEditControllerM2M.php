<?php

namespace App\Http\Controllers\Entities;

use App\Helpers\Helper;
use App\Utils\Support\Relationships;

trait CreateEditControllerM2M
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
        $newDataInputHasArray = array_filter($dataInput, fn ($item) => is_array($item));

        foreach ($newDataInputHasArray as $key => $value) {
            $valueInt = array_map(fn ($i) => $i * 1, $value);

            $termModelPath = $data->oracyParams[$key][1];
            $fixFieldName = str_replace('()', '', $key);
            $data->syncCheck($allFields[$fixFieldName], $termModelPath, $valueInt);
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
