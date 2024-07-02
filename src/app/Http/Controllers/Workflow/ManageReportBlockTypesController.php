<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\JsonControls;

class ManageReportBlockTypesController extends AbstractManageLibController
{
    protected $title = 'Manage Report Block Type';
    protected $libraryClass = LibReports::class;
    protected $route = 'manageReportBlockTypes';
    protected $groupByLength = 6;

    protected function getColumns()
    {
        $packages = JsonControls::getPackages();
        $breadcrumbGroups = JsonControls::getBreadcrumbGroup();
        // $subPackages = JsonControls::getSubPackages();
        // $subPackages = Entities::getAllPluralNames();
        return   [
            [
                'dataIndex' => 'action',
                'align' => 'center',
            ],
            [
                'title' => 'Block',
                'dataIndex' => 'block_id',
                'cbbDataSource' => ['', 'Table', 'Chart', 'Paragraph', 'Description'],
                'renderer'  => 'dropdown',
                'editable' => true,
                'width' => 100,
            ],
            [
                'title' => 'Page',
                'dataIndex' => 'page_id',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'title' => 'Report',
                'dataIndex' => 'report_id',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'col_span',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'order_no',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'background',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'sql_string',
                'renderer'  => 'textarea4',
                'editable' => true,
                'width' => 300,
            ],
            [
                'dataIndex' => 'table_true_width',
                'renderer'  => 'checkbox',
                'align' => 'center',
                'editable' => true,
                'width' => 50,
            ],
            [
                'dataIndex' => 'max_h',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'rotate_45_width',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'rotate_45_height',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'title' => 'Pagination',
                'dataIndex' => 'has_pagination',
                'renderer'  => 'checkbox',
                'align' => 'center',
                'editable' => true,
                'width' => 50,
            ],
            [
                'dataIndex' => 'top_left_control',
                'renderer'  => 'dropdown',
                'cbbDataSource' => ['', 'Export', 'Pagination', 'Paragraph'],
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'top_center_control',
                'renderer'  => 'dropdown',
                'cbbDataSource' => ['', 'Export', 'Pagination', 'Paragraph'],
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'top_right_control',
                'renderer'  => 'dropdown',
                'cbbDataSource' => ['', 'Export', 'Pagination', 'Paragraph'],
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'bottom_left_control',
                'renderer'  => 'dropdown',
                'cbbDataSource' => ['', 'Export', 'Pagination', 'Paragraph'],
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'bottom_center_control',
                'renderer'  => 'dropdown',
                'cbbDataSource' => ['', 'Export', 'Pagination', 'Paragraph'],
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'bottom_right_control',
                'renderer'  => 'dropdown',
                'cbbDataSource' => ['', 'Export', 'Pagination', 'Paragraph'],
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'chart_type',
                'renderer'  => 'dropdown',
                'cbbDataSource' => ['', 'Line', 'Area', 'Bar', 'Column', 'BoxPlot', 'Heat Map'],
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'html_content',
                'renderer'  => 'textarea4',
                'editable' => true,
                'width' => 200,
            ],
            [
                'dataIndex' => 'html_attachment',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],

        ];
    }
}
