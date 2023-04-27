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
                'dataIndex' => "hidden",
                "renderer"  => 'dropdown',
                'editable' => true,
                "width" => 100,
            ],
            [
                'dataIndex' => "chart_type",
                "renderer"  => 'dropdown',
                'editable' => true,
                'cbbDataSource' => ['', 'line', 'bar', 'pie', 'doughnut'],
                'sortBy' => 'value',
                "width" => 100,
            ],
            [
                'dataIndex' => "fn",
                'renderer' => 'dropdown',
                'editable' => true,
                "width" => 100,
                'cbbDataSource' => [
                    '',
                    'SqlForeignKey',
                    'SqlTest',

                ],
                "properties" => ['strFn' => 'same'],
            ],
            [
                'dataIndex' => "section_title",
                'renderer' => 'dropdown',
                'editable' => true,
                'cbbDataSource' => $apps,
                'sortBy' => 'title',
                "width" => 100,
            ],
            [
                'dataIndex' => "widget_title",
                'renderer' => 'dropdown',
                'editable' => true,
                'cbbDataSource' => $apps,
                'sortBy' => 'title',
                "width" => 100,
            ],
            [
                'dataIndex' => "params",
                'renderer' => 'textarea4',
                'editable' => true,
                "width" => 500,
            ],
        ];
    }
}
