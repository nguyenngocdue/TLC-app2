<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class AbstractManageLibController extends Controller
{
    protected $title = "Manage {Library}";
    protected $libraryClass = AbstractLib::class;
    protected $route = "manage{Library}";

    public function __construct()
    {
    }

    public function getType()
    {
        return "workflow";
    }

    abstract protected function getColumns();

    private function distributeArrayToObject($array)
    {
        $result = [];
        foreach ($array as $index => $attributes) {
            foreach ($attributes as $key => $value) {
                $name = $array['name'][$key];
                $result[$name][$index] = $value;
            }
        }
        // Log::info($result);
        return $result;
    }

    public function index()
    {
        $columns = $this->getColumns();
        $dataSource = array_values($this->libraryClass::getAll());
        $route = $this->route;
        $title = $this->title;

        return view("workflow/manage-statuses")->with(compact('title', 'columns', 'dataSource', 'route'));
    }

    public function store(Request $request)
    {
        $dataSource = (array)$request->all();
        unset($dataSource["_token"]);
        $dataSource = $this->distributeArrayToObject($dataSource);
        // dd($dataSource);
        $this->libraryClass::setAll($dataSource);
        return redirect()->back();
    }

    public function create(Request $request)
    {
        $name = $request->input('name')[0];
        $names = explode("|", $name);
        $newItems = [];
        foreach ($names as $name) $newItems[$name] = ['name' => $name, 'title' => Str::headline($name)];

        $dataSource = $this->libraryClass::getAll()  + $newItems;
        // dd($dataSource);
        $this->libraryClass::setAll($dataSource);
        return redirect()->back();
    }
}
