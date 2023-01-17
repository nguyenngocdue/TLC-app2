<?php

namespace App\Http\Controllers\Workflow;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

abstract class AbstractManageLibController extends Controller
{
    protected $title = "Manage {Library}";
    protected $libraryClass = AbstractLib::class;
    protected $route = "manage{Library}";

    public function getType()
    {
        return "workflow";
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

    public function getDataSource()
    {
        $result = array_values($this->libraryClass::getAll());
        foreach ($result as $key => &$value) {
            $value['action'] = Blade::render("<div class='whitespace-nowrap'>
            <x-renderer.button htmlType='submit' name='button' size='xs' value='right_by_name,$key' type='danger' outline=true><i class='fa fa-trash'></i></x-renderer.button>
        </div>");
        }
        return $result;
    }

    public function index()
    {
        return view("workflow/manage-library", [
            'columns' => $this->getColumns(),
            'dataSource' => $this->getDataSource(),
            'route' => $this->route,
            'title' => $this->title,
        ]);
    }

    private function delete($button, $dataSource)
    {
        [$action, $index] = explode(",", $button);
        $dataSource = array_values($dataSource);
        if ($action === 'right_by_name') unset($dataSource[$index]);
        $newDataSource = [];
        foreach ($dataSource as $item)  $newDataSource[$item['name']] = $item;
        return $newDataSource;
    }

    public function store(Request $request)
    {
        $dataSource = (array)$request->all();
        $button = $dataSource['button'];
        unset($dataSource["_token"]);
        unset($dataSource["button"]);

        $dataSource = $this->distributeArrayToObject($dataSource);
        $dataSource = $this->delete($button, $dataSource);

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
