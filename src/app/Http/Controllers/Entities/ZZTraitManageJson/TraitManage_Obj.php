<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use Illuminate\Http\Request;

trait TraitManage_Obj
{
    function indexObj(Request $request, string $view, string $key)
    {
        $obj = $this->pages[$key];
        return view($view, [
            'title' => $this->getTitle($request),
            'type' => $this->type,
            'route' => route($this->type . $key . '.store'),
            'columns' => $this->{"getColumns$obj"}(),
            'dataSource' => array_values($this->{"getDataSource$obj"}()),
        ]);
    }

    function storeObj(Request $request, string $jsonGetSet, $key, array $excluded = [])
    {
        $obj = $this->pages[$key];
        $data = $request->input();
        $excluded = ['action', ...$excluded,];
        $columns = array_filter($this->{"getColumns$obj"}(), fn ($column) => !in_array($column['dataIndex'], $excluded));
        $result = $jsonGetSet::convertHttpObjectToJson($data, $columns);
        $this->handleMoveTo($result);
        if ($request->input('button')) {
            [$direction, $name] = explode(",", $request->input('button'));
            $jsonGetSet::move($result, $direction, $name);
        }
        $jsonGetSet::setAllOf($this->type, $result);
        return back();
    }

    function createObj(Request $request, string $jsonGetSet)
    {
        $name = $request->input('name')[0];
        $names = explode("|", $name);
        $newItems = [];
        foreach ($names as $name) $newItems[$name] = [
            'name' => "_" . $name,
            'column_name' => $name,
        ];
        $dataSource = $jsonGetSet::getAllOf($this->type) + $newItems;
        $jsonGetSet::setAllOf($this->type, $dataSource);
        return back();
    }
}
