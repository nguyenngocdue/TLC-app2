<?php

namespace App\Http\Controllers\Workflow;

class ManageWidgetsController extends AbstractManageLibController
{
    protected $title = "Manage Widgets";
    protected $libraryClass = LibWidgets::class;
    protected $route = "manageWidgets";

    protected function getColumns()
    {
        $apps0 = LibApps::getAll();
        $apps = [
            [
                'title' => '',
                'value' => null,
            ]
        ];
        foreach ($apps0 as $key => $app) {
            $apps[] = [
                'title' => $app['title'],
                'value' => $key,
            ];
        }

        $allNameReports = [''] + array_keys(LibReports::getAll());
        // dump($apps);
        return   [
            [
                "dataIndex" => "action",
                "align" => "center",
                "width" => 10,
            ],
            [
                'dataIndex' => "name",
                "renderer"  => 'read-only-text4',
                'editable' => true,
                "width" => 100,
            ],
            [
                'dataIndex' => "report_name",
                "renderer"  => 'dropdown',
                'editable' => true,
                "width" => 100,
                'cbbDataSource' => $allNameReports
            ],
            [
                'dataIndex' => "hidden",
                "renderer"  => 'dropdown',
                'editable' => true,
                "width" => 100,
            ],
            [
                'dataIndex' => "chart_type",
                "renderer"  => 'dropdown',
                'editable' => true,
                'cbbDataSource' => ['', 'line', 'bar', 'horizontal_bar','bar_two_columns', 'pie', 'doughnut'],
                'sortBy' => 'value',
                "width" => 100,
            ],
           
            // [
            //     'dataIndex' => "fn",
            //     'renderer' => 'dropdown',
            //     'editable' => true,
            //     "width" => 100,
            //     'cbbDataSource' => [
            //         '',
            //         'SqlForeignKey',
            //         'SqlForeignKeyWidget01',
            //         'SqlTest',
            //         'SqlStatus',

            //     ],
            //     "properties" => ['strFn' => 'same'],
            // ],
            [
                'dataIndex' => "params",
                'renderer' => 'textarea4',
                'editable' => true,
                "width" => 500,
            ],
            [
                'dataIndex' => "param_meta",
                'renderer' => 'textarea4',
                'editable' => true,
                "width" => 500,
            ],
            [
                'dataIndex' => "line_series",
                'renderer' => 'textarea4',
                'editable' => true,
                "width" => 500,
            ],
            [
                'dataIndex' => "dimensions",
                'renderer' => 'textarea4',
                'editable' => true,
                "width" => 500,
            ],
        ];
    }
}
