<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;

class Pages
{
    const Prop = "Prop";
    const Listener = "Listener";
    const Relationship = "Relationship";
    const Status = "Status";
    const Transition = "Transition";
    const ActionButton = "ActionButton";
    const Setting = "Setting";
    const BallInCourt = "BallInCourt";
    const Visibility = "Visibility";
    const UnitTest = "UnitTest";
}

abstract class Manage_Parent
{
    private $pages = [
        "_prp" => Pages::Prop,
        "_ltn" => Pages::Listener,
        "_stt" => Pages::Status,
        "_rls" => Pages::Relationship,
        "_tst" => Pages::Transition,
        "_atb" => Pages::ActionButton,
        "_stn" => Pages::Setting,
        "_bic" => Pages::BallInCourt,
        "_vsb" => Pages::Visibility,
        "_unt" => Pages::UnitTest,
    ];

    protected $viewName;
    protected $routeKey;
    protected $jsonGetSet;
    protected $excludedColumnsFromStoring = [];

    protected abstract function getColumns();
    protected abstract function getDataSource();

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
            'title' => "ABCDEF",
            // 'title' => $this->getTitle($request),
            'type' => $this->type,
            'route' => route($this->type . $this->routeKey . '.store'),
            'columns' => $this->getColumns(),
            'dataSource' => array_values($this->getDataSource()),
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
        $obj = $this->pages[$this->routeKey];
        $data = $request->input();

        //Make up the columns
        $columns = $this->getColumns();
        $columns = array_filter($columns, fn ($column) => !in_array($column['dataIndex'], ['action', ...$this->excludedColumnsFromStoring,]));

        $result = $jsonGetSet::convertHttpObjectToJson($data, $columns);
        $this->handleMoveTo($result, $jsonGetSet);
        if ($request->input('button')) {
            [$direction, $name] = explode(",", $request->input('button'));
            $jsonGetSet::move($result, $direction, $name);
        }
        $jsonGetSet::setAllOf($this->type, $result);
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
        return back();
    }
}
