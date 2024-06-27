<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\JsonControls;

class ManageReportColumnsController extends AbstractManageLibController
{
    protected $title = "Manage Report Columns";
    protected $libraryClass = LibReports2::class;
    protected $route = "manageReportColumns";
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
                'title' => 'Column',
                'dataIndex' => "data_index",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'title' => 'Block',
                'dataIndex' => "block_id",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'title' => 'Page',
                'dataIndex' => "page_id",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'title' => 'Report',
                'dataIndex' => "report_id",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'title' => 'Active',
                'dataIndex' => "is_active",
                "renderer"  => 'checkbox',
                'editable' => true,
                'align' => 'center',
                'width' => 40,
            ],
            [
                'dataIndex' => "width",
                "renderer"  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => "cell_div_class_agg_footer",
                "renderer"  => 'textarea4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "cell_div_class",
                "renderer"  => 'textarea4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "cell_class",
                "renderer"  => 'textarea4',
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
                'cbbDataSource' => ['', 'top', 'bottom', 'left', 'right'],
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "row_cell_div_class",
                "renderer"  => 'textarea4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => "row_cell_class",
                "renderer"  => 'textarea4',
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
                'cbbDataSource' => ['', 'top', 'bottom', 'left', 'right'],
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => "row_href_fn",
                "renderer"  => 'textarea4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "row_renderer",
                "renderer"  => 'dropdown',
                'cbbDataSource' => ['', 'Id', 'Tag (status)', 'Normal', 'Empty Icon'],
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => "agg_footer",
                "renderer"  => 'dropdown',
                'cbbDataSource' => ['', 'Sum', 'Unique Item Sum', 'Average', 'Unique Count', 'Total Count'],
                'editable' => true,
                'width' => 200,
            ],

        ];
    }
}
