<?php

namespace App\Http\Controllers\Entities\ZZTraitManageJson;

use App\Http\Controllers\Workflow\LibStatuses;
use App\Utils\Support\CurrentRoute;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class ManageStatuses extends Manage_Parent
{
    protected function getColumns()
    {
        return [
            [
                'dataIndex' => 'name',
            ],
            [
                'title' => "Current Statuses",
                'dataIndex' => "title",
                "renderer" => "tag",
                "attributes" => ['color' => 'color', 'colorIndex' => 'color_index'],
                'align' => 'right',
            ],
            [
                'dataIndex' => 'action',
                'align' => 'right',
            ],

        ];
    }

    protected function getDataSource()
    {
        $plural = Str::plural($this->type);
        $dataSource0 = LibStatuses::getFor($plural);
        foreach ($dataSource0 as &$line) {
            $value = $line['name'];
            $line['action'] = Blade::render("<div>
                <x-renderer.button htmlType='submit' name='button' size='xs' value='up,$value'><i class='fa fa-arrow-up'></i></x-renderer.button>
                <x-renderer.button htmlType='submit' name='button' size='xs' value='down,$value'><i class='fa fa-arrow-down'></i></x-renderer.button>
                <x-renderer.button htmlType='submit' name='button' size='xs' value='right,$value' type='primary' ><i class='fa fa-arrow-right'></i></x-renderer.button>
            </div>
            ");
        }
        return $dataSource0;
    }

    private function getColumnsRight()
    {
        return [
            [
                'dataIndex' => 'action'
            ],
            [
                'title' => "Available Statuses",
                'dataIndex' => "title",
                "renderer" => "tag",
                "attributes" => ['color' => 'color', 'colorIndex' => 'color_index'],
            ],
        ];
    }

    private function getDataSourceRight($dataSource0)
    {
        $dataSource1 = LibStatuses::getAll();
        $dataSource1 = array_diff_key($dataSource1, $dataSource0);
        foreach ($dataSource1 as &$line) {
            $value = $line['name'];
            $line['action'] = Blade::render("<div>
                <x-renderer.button htmlType='submit' name='button' size='xs' value='left,$value' type='primary' ><i class='fa fa-arrow-left'></i></x-renderer.button>
            </div>
            ");
        }
        return $dataSource1;
    }

    public function index(Request $request)
    {
        $dataSource0 = $this->getDataSource();
        $dataSource1 = $this->getDataSourceRight($dataSource0);

        return view("dashboards.pages.manage-status", [
            'title' => 'Manage Workflows',
            'topTitle' => CurrentRoute::getTitleOf($this->type),
            'type' => $this->type,
            'route' => route($this->type . '_stt.store'),
            'routeManage' => route("manageStatuses.index"),

            'columns0' => $this->getColumns(),
            'dataSource0' => array_values($dataSource0),
            'columns1' => $this->getColumnsRight(),
            'dataSource1' => array_values($dataSource1),
        ]);
    }

    public function store(Request $request)
    {
        if ($request->input('button')) {
            [$direction, $name] = explode(",", $request->input('button'));
            try {
                $result = LibStatuses::move($direction, $this->type, $name);
                if (!$result) {
                    dump(error_get_last());
                }
            } catch (\Throwable $th) {
                toastr()->warning("Saved failed. Maybe Permission is missing!", 'Failed');
            }
        }
        return back();
    }
}
