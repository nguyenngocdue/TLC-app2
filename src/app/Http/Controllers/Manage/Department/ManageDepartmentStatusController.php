<?php

namespace App\Http\Controllers\Manage\Department;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Workflow\Statuses;
use Illuminate\Support\Facades\Blade;

class ManageDepartmentStatusController extends Controller
{
    public function getType()
    {
        return "department";
    }

    private function getColumns0()
    {
        return [
            [
                'title' => "Current Statuses",
                'dataIndex' => "title",
                "renderer" => "tag", "attributes" => ['color' => 'color'],
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
                "renderer" => "tag", "attributes" => ['color' => 'color'],
            ],
        ];
    }

    public function index()
    {
        $dataSource0 = Statuses::getFor('departments');
        foreach ($dataSource0 as &$line) {
            $line['action'] = Blade::render("<div>
            <x-renderer.button><i class='fa fa-arrow-up'></i></x-renderer.button>
            <x-renderer.button><i class='fa fa-arrow-down'></i></x-renderer.button>
                <x-renderer.button type='primary'><i class='fa fa-arrow-right'></i></x-renderer.button>
            </div>
            ");
        }
        $dataSource1 = Statuses::getAll();
        $dataSource1 = array_diff_key($dataSource1, $dataSource0);
        foreach ($dataSource1 as &$line) {
            $line['action'] = Blade::render("<div>
                <x-renderer.button type='primary'><i class='fa fa-arrow-left'></i></x-renderer.button>
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
}
