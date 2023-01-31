<?php

namespace App\Http\Controllers\Entities\ZZTraitEntity;

trait TraitEntityListenDataSource
{
    private function renderListenDataSource()
    {
        $sp = $this->superProps;
        $toBeLoaded = [];
        foreach ($sp['props'] as $prop) {
            $relationships = $prop['relationships'];
            if (isset($relationships['eloquentParams']))
                $toBeLoaded[] = $relationships['eloquentParams'][1];
            if (isset($relationships['oracyParams']))
                $toBeLoaded[] = $relationships['oracyParams'][1];
            if (sizeof($prop['listeners']) > 0) {
            }
        }

        dump($sp);
        dump($toBeLoaded);
        // $listeners = Listeners::getAllOf($this->type);
        // dump($listeners);
        return [
            ["id" => 1, "name" => "ABC"],
            ["id" => 2, "name" => "DEF1"],
        ];
    }
}
