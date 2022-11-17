<?php

namespace App\Http\Controllers\Render;

trait CreateEditControllerM2M
{
    private function getRelationships()
    {
        $instance = new $this->data;
        $eloquentParams = $instance->eloquentParams;
        $relationships = $this->readingFileService->type_getPath($this->disk,  $this->branchName, $instance->getTable(), "relationships.json");
        return [$relationships, $eloquentParams];
    }

    private function syncManyToManyRelationship($data = null, $dataInput = []) //checkBox
    {
        [$relationships, $eloquentParams] = $this->getRelationships();

        if ($eloquentParams) {
            $itemIds = [];
            foreach ($eloquentParams as $fn => $value) {
                if ($value[0] === "belongsToMany") {
                    $colNamePivots = "";
                    foreach ($relationships as $relationship) {
                        if ($relationship['relationship'] === $fn) {
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

    private function getManyToManyRelationship($currentElement) //checkBox
    {
        [$relationships, $eloquentParams] = $this->getRelationships();

        if ($eloquentParams) {
            $itemIds = [];
            foreach ($eloquentParams as $fn => $value) {
                if ($value[0] === "belongsToMany") {
                    foreach ($relationships as $relationship) {
                        if ($relationship['relationship'] === $fn) {
                            $itemIds[$relationship['control_name']] = json_decode($currentElement->$fn->pluck('id'));
                        }
                    }
                }
            }
            return $itemIds;
        }
    }
}
