<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

trait TraitManage_Obj
{
    function attachActionButtons(&$dataSource, $key, array $buttons)
    {
        $results = [];
        foreach ($buttons as $button) {
            switch ($button) {
                case "up":
                    $results[] = "<x-renderer.button htmlType='submit' name='button' size='xs' value='up,$key'><i class='fa fa-arrow-up'></i></x-renderer.button>";
                    break;
                case "down":
                    $results[] = "<x-renderer.button htmlType='submit' name='button' size='xs' value='down,$key'><i class='fa fa-arrow-down'></i></x-renderer.button>";
                    break;
                case "right_by_name":
                    $results[] = "<x-renderer.button htmlType='submit' name='button' size='xs' value='right_by_name,$key' type='danger' outline=true><i class='fa fa-trash'></i></x-renderer.button>";
                    break;
            }
        }
        $result = join(" ", $results);
        $dataSource[$key]['action'] = Blade::render("<div class='whitespace-nowrap'>$result</div>");
    }

    function indexObj(Request $request, string $view, string $key)
    {
        $obj = $this->pages[$key];
        $columns = $this->{"getColumns$obj"}();
        $dataSource = $this->{"getDataSource$obj"}();
        // $dataSource = $this->postEnrichDataSource($dataSource);

        return view($view, [
            'title' => $this->getTitle($request),
            'type' => $this->type,
            'route' => route($this->type . $key . '.store'),
            'columns' => $columns,
            'dataSource' => array_values($dataSource),
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
