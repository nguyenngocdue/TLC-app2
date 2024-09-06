<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\CurrentRoute;
use App\Utils\Support\Entities;
use App\Utils\Support\Json\JsonGetSet;
use App\Utils\Support\Json\SuperProps;
use App\Utils\Support\Json\SuperWorkflows;
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
    protected $headerTop = null;

    protected $showToggleColumn = false;
    protected $showToggleRow = false;

    protected abstract function getColumns();
    protected abstract function getDataSource();
    protected function getDataHeader()
    {
        return [];
    }

    function __construct(
        protected $type,
        protected $typeModel,
    ) {}

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

    function makeUpWidthForColumns(&$columns)
    {
        foreach ($columns as &$column) {
            if (!isset($column['width'])) $column['width'] = 100;
        }
    }

    protected function getColumnSource()
    {
        return LibStatuses::getFor($this->type);
    }

    private function getJavascript()
    {
        $allStatuses = array_keys($this->getColumnSource());
        $allStatusesStr = "[" . join(", ", array_map(fn($i) => '"' . $i . '"', $allStatuses)) . "]";
        $javascript = "const statuses = $allStatusesStr;";
        $javascript .= "let k_horizon_mode = {}; let k_horizon_value = {};";
        $javascript .= "let k_vertical_mode = {}; let k_vertical_value = {};";
        return "<script>$javascript</script>";
    }

    protected function getMoreJS()
    {
        return "";
    }

    function index(Request $request)
    {
        if (app()->isProduction()) abort(403, "All Manage Workflow Screens are not available on production.");
        $columns = $this->getColumns();
        $this->makeUpWidthForColumns($columns);
        $jsStatusArray = $this->getJavascript();
        $jsStatusArray2 = $this->getMoreJS();
        return view($this->viewName, [
            'title' => "Manage Workflows",
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'type' => $this->type,
            'route' => route($this->type . $this->routeKey . '.store'),
            'columns' => $columns,
            'dataSource' => array_values($this->getDataSource()),
            'dataHeader' => $this->getDataHeader(),
            'headerTop' => $this->headerTop,
            'jsStatusArray' => $jsStatusArray,
            'jsStatusArray2' => $jsStatusArray2,
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

    function invalidateCache()
    {
        SuperProps::invalidateCache($this->type);
        SuperWorkflows::invalidateCache($this->type);
        if ($this->jsonGetSet == 'App\Utils\Support\Json\Properties') {
            $allEntities = Entities::getAllSingularNames();
            foreach ($allEntities as $entity) {
                // dump("Invalidating $entity");
                SuperProps::invalidateCache($entity);
            }
            // dd("Invalidate all");
        }
    }

    function store(Request $request)
    {
        $jsonGetSet = $this->jsonGetSet;
        $data = $request->input();
        $table01 = $data['table01'] ?? [];

        //Make up the columns
        $columns = $this->getColumns();

        $this->makeUpWidthForColumns($columns);
        //Remove all things in blacklist
        $columns = array_filter($columns, fn($column) => !in_array($column['dataIndex'], ['action', ...$this->storingBlackList,]));
        //Remove all things NOT in whitelist
        if (sizeof($this->storingWhiteList) > 0) {
            $columns = array_filter($columns, fn($column) => in_array($column['dataIndex'], $this->storingWhiteList));
        }
        // dd($this->storingWhiteList);
        $result = $jsonGetSet::convertHttpObjectToJson($table01, $columns);
        $this->handleMoveTo($result, $jsonGetSet);
        if ($request->input('button')) {
            [$direction, $name] = explode(",", $request->input('button'));
            $jsonGetSet::move($result, $direction, $name);
        }
        $jsonGetSet::setAllOf($this->type, $result);

        $this->invalidateCache();


        return back();
    }

    function create(Request $request)
    {
        $table02 = $request->input('table02');
        $name = $table02['name'][0];
        $names = explode("|", $name);
        $newItems = [];
        foreach ($names as $name) $newItems[$name] = [
            'name' => "_" . $name,
            'column_name' => $name,
        ];
        $dataSource = $this->jsonGetSet::getAllOf($this->type) + $newItems;
        $this->jsonGetSet::setAllOf($this->type, $dataSource);
        $this->invalidateCache();
        return back();
    }
}
