<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Utils\TraitManageDefaultValueColumns;
use App\Http\Controllers\Workflow\LibStandardDefaultValues;
use App\Utils\Support\Json\DefaultValues;
use App\Utils\Support\Json\Props;

class ManageDefaultValues extends Manage_Parent
{
    use TraitManageDefaultValueColumns;

    protected $viewName = "dashboards.pages.manage-default-value";
    protected $routeKey = "_dfv";
    protected $jsonGetSet = DefaultValues::class;

    public function getDataSource()
    {
        $allProps = Props::getAllOf($this->type);
        $dataInJson = DefaultValues::getAllOf($this->type);
        $standardDefaultValues = LibStandardDefaultValues::getAll();
        $standardDefaultValueKeys = array_keys($standardDefaultValues);
        $result = [];
        foreach ($allProps as $prop) {
            $name = $prop['name'];
            if (isset($dataInJson[$name])) {
                $newItem = $dataInJson[$name];
            } else {
                $newItem = [
                    'name' => $name,
                    'column_name' => $prop['column_name'],
                ];
            }
            $newItem['label'] = $prop['label'];
            if (isset($prop['column_type']) && $prop['column_type'] === 'static') $newItem['row_color'] = "amber";
            if (in_array($prop['name'], $standardDefaultValueKeys)) $newItem['row_color'] = "gray";
            $result[] = $newItem;
        }
        return $result;
    }
}
