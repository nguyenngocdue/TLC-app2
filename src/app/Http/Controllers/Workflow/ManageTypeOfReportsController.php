<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\JsonControls;

class ManageTypeOfReportsController extends AbstractManageLibController
{
    protected $title = "Manage Column Reports";
    protected $libraryClass = LibReports::class;
    protected $route = "manageColumnReports";
    protected $groupByLength = 6;

    protected function getColumns()
    {
        $packages = JsonControls::getPackages();
        $breadcrumbGroups = JsonControls::getBreadcrumbGroup();
        // $subPackages = JsonControls::getSubPackages();
        // $subPackages = Entities::getAllPluralNames();
        return   [
            [
                "dataIndex" => "action",
                "align" => "center",
            ],
            [
                'dataIndex' => "name",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 300,
            ],
            [
                'dataIndex' => "is_active",
                "renderer"  => 'dropdown',
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => "data_index",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "width",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "cell_div_class_agg_footer",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "cell_div_class",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "cell_class",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "icon",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "icon_position",
                "renderer"  => 'dropdown',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "row_cell_div_class",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "row_cell_class",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "row_icon",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "row_icon_position",
                "renderer"  => 'dropdown',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "row_href_fn",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "row_renderer",
                "renderer"  => 'dropdown',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "agg_footer",
                "renderer"  => 'dropdown',
                'editable' => true,
                'width' => 200,
            ],

        ];
    }
}
