<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Utils\Support\Json\JsonGetSet;
use App\Utils\Support\Json\SuperProps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

abstract class Manage_Parent
{
    protected $viewName;
    protected $routeKey;
    /** @var JsonGetSet $jsonGetSet */
    protected $jsonGetSet;
    protected $storingWhiteList = [];
    protected $storingBlackList = [];
    protected $headerTop = 0;

    protected abstract function getColumns();
    protected abstract function getDataSource();
    protected function getDataHeader()
    {
        return [];
    }

    function __construct(
        protected $type,
        protected $typeModel,
    ) {
    }

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

    function index(Request $request)
    {
        return view($this->viewName, [
            'title' => "Manage Workflows",
            'type' => $this->type,
            'route' => route($this->type . $this->routeKey . '.store'),
            'columns' => $this->getColumns(),
            'dataSource' => array_values($this->getDataSource()),
            'dataHeader' => $this->getDataHeader(),
            'headerTop' => $this->headerTop,
        ]);
    }

    private function handleMoveTo(&$result)
    {
        foreach ($result as $key => $line) {
            if (isset($line['move_to']) && is_numeric($line['move_to'])) {
                $this->jsonGetSet::moveTo($result, $line['move_to'] - 1, $key);
            }
        }
        foreach ($result as &$line) unset($line['move_to']);
    }

    function store(Request $request)
    {
        $jsonGetSet = $this->jsonGetSet;
        $data = $request->input();

        //Make up the columns
        $columns = $this->getColumns();
        //Remove all things in blacklist
        $columns = array_filter($columns, fn ($column) => !in_array($column['dataIndex'], ['action', ...$this->storingBlackList,]));
        //Remove all things NOT in whitelist
        if (sizeof($this->storingWhiteList) > 0) {
            $columns = array_filter($columns, fn ($column) => in_array($column['dataIndex'], $this->storingWhiteList));
        }

        $result = $jsonGetSet::convertHttpObjectToJson($data, $columns);
        $this->handleMoveTo($result, $jsonGetSet);
        if ($request->input('button')) {
            [$direction, $name] = explode(",", $request->input('button'));
            $jsonGetSet::move($result, $direction, $name);
        }
        $jsonGetSet::setAllOf($this->type, $result);
        SuperProps::invalidateCache($this->type);
        return back();
    }

    function create(Request $request)
    {
        $name = $request->input('name')[0];
        $names = explode("|", $name);
        $newItems = [];
        foreach ($names as $name) $newItems[$name] = [
            'name' => "_" . $name,
            'column_name' => $name,
        ];
        $dataSource = $this->jsonGetSet::getAllOf($this->type) + $newItems;
        $this->jsonGetSet::setAllOf($this->type, $dataSource);
        SuperProps::invalidateCache($this->type);
        return back();
    }
}
