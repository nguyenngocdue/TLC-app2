<?php

namespace App\Http\Controllers\Entities;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Workflow\Statuses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

abstract class AbstractStatusController extends Controller
{
    public function getType()
    {
        return $this->type;
    }

    private function getColumns0()
    {
        return [
            [
                'title' => "Current Statuses",
                'dataIndex' => "title",
                "renderer" => "tag", "attributes" => ['color' => 'color', 'colorIndex' => 'color_index'],
                'align' => 'right',
            ],
            [
                'dataIndex' => 'action',
                'align' => 'right',
            ],

        ];
    }

    private function getColumns1()
    {
        return [
            [
                'dataIndex' => 'action'
            ],
            [
                'title' => "Available Statuses",
                'dataIndex' => "title",
                "renderer" => "tag", "attributes" => ['color' => 'color', 'colorIndex' => 'color_index'],
            ],
        ];
    }

    public function index()
    {
        $plural = Str::plural($this->type);
        $dataSource0 = Statuses::getFor($plural);
        foreach ($dataSource0 as &$line) {
            $value = $line['name'];
            $line['action'] = Blade::render("<div>
                <x-renderer.button htmlType='submit' name='button' size='xs' value='up,$value'><i class='fa fa-arrow-up'></i></x-renderer.button>
                <x-renderer.button htmlType='submit' name='button' size='xs' value='down,$value'><i class='fa fa-arrow-down'></i></x-renderer.button>
                <x-renderer.button htmlType='submit' name='button' size='xs' value='right,$value' type='primary' ><i class='fa fa-arrow-right'></i></x-renderer.button>
            </div>
            ");
        }
        $dataSource1 = Statuses::getAll();
        $dataSource1 = array_diff_key($dataSource1, $dataSource0);
        foreach ($dataSource1 as &$line) {
            $value = $line['name'];
            $line['action'] = Blade::render("<div>
                <x-renderer.button htmlType='submit' name='button' size='xs' value='left,$value' type='primary' ><i class='fa fa-arrow-left'></i></x-renderer.button>
            </div>
            ");
        }

        return view("dashboards.pages.manage-status", [
            'columns0' => $this->getColumns0(),
            'dataSource0' => array_values($dataSource0),
            'columns1' => $this->getColumns1(),
            'dataSource1' => array_values($dataSource1),
        ]);
    }

    public function store(Request $request)
    {
        $type = $this->getType();
        if ($request->input('button')) {
            [$direction, $name] = explode(",", $request->input('button'));
            Statuses::move($direction, $type, $name);
        }
        return redirect(route($type . '_stt.index'));
    }
}
