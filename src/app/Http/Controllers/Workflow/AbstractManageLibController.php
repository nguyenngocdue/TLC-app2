<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class AbstractManageLibController extends Controller
{
    protected $title = "Manage {Library}";
    protected $libraryClass = AbstractLib::class;
    protected $route = "manage{Library}";
    protected $allowedCreateNew = true;
    protected $groupBy = 'name';
    protected $groupByLength = 1;

    public function getType()
    {
        return $this->route;
    }

    abstract protected function getColumns();

    private function distributeArrayToObject($array)
    {
        $result = [];
        foreach ($array as $index => $attributes) {
            // if (in_array($index, ['button'])) continue;
            foreach ($attributes as $key => $value) {
                $name = $array['name'][$key];
                $result[$name][$index] = $value;
            }
        }
        // Log::info($result);
        return $result;
    }

    protected function getDataSource()
    {
        $result = array_values($this->libraryClass::getAll());
        return $result;
    }

    protected function renderDataSource()
    {
        $result = $this->getDataSource();
        foreach ($result as &$value) {
            $key = $value['name'];
            $value['action'] = Blade::render("<div class='whitespace-nowrap'>
            <x-renderer.button htmlType='submit' name='button' size='xs' value='right_by_name,$key' type='danger' outline=true><i class='fa fa-trash'></i></x-renderer.button>
        </div>");
        }
        return $result;
    }

    public function index()
    {
        if (app()->isProduction()) abort(403, "All Manage Lib Screens are not available on production.");
        return view("dashboards.pages.manage-library", [
            'columns' => $this->getColumns(),
            'dataSource' => $this->renderDataSource(),
            'route' => $this->route,
            'title' => '',
            'topTitle' => $this->title,
            'type' => 'Workflow',
            'allowedCreateNew' => $this->allowedCreateNew,
            'groupBy' => $this->groupBy,
            'groupByLength' => $this->groupByLength,
        ]);
    }

    private function delete($button, $dataSource)
    {
        [$direction, $key] = explode(",", $button);
        $dataSource = Arr::moveDirection($dataSource, $direction, null, $key);
        $dataSource = Arr::keyBy($dataSource, "name");
        return $dataSource;
    }

    public function store(Request $request)
    {
        $dataSource = (array)$request->all();
        unset($dataSource["_token"]);
        if (isset($dataSource['button'])) {
            $button = $dataSource['button'];
            unset($dataSource["button"]);
        }

        $table01 = $dataSource['table01'];
        $dataSource = $this->distributeArrayToObject($table01);
        if ($request->input('button')) {
            $dataSource = $this->delete($button, $dataSource);
        }

        $this->libraryClass::setAll($dataSource);
        return redirect()->back();
    }

    public function create(Request $request)
    {
        $table02 = $request->input('table02');
        $name = $table02['name'][0];
        $names = explode("|", $name);
        $newItems = [];
        foreach ($names as $name) $newItems[$name] = ['name' => $name, 'title' => Str::headline($name)];

        $dataSource = $this->libraryClass::getAll()  + $newItems;
        // dd($dataSource);
        $this->libraryClass::setAll($dataSource);
        return redirect()->back();
    }
}
