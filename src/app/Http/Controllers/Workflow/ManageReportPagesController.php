<?php

namespace App\Http\Controllers\Workflow;

class ManageReportPagesController extends AbstractManageLibController
{
    protected $title = 'Manage Report Pages';
    protected $libraryClass = LibReports::class;
    protected $route = 'manageReportPages';
    protected $groupByLength = 6;

    protected function getColumns()
    {
        // $packages = JsonControls::getPackages();
        // $breadcrumbGroups = JsonControls::getBreadcrumbGroup();
        // $subPackages = JsonControls::getSubPackages();
        // $subPackages = Entities::getAllPluralNames();
        return   [
            [
                'dataIndex' => 'action',
                'align' => 'center',
            ],
            [
                'title' => 'Page',
                'dataIndex' => 'block_id',
                'cbbDataSource' => ['', '01', '02', '03'],
                'renderer'  => 'dropdown',
                'editable' => true,
                'width' => 100,
            ],
            [
                'title' => 'Report',
                'dataIndex' => 'report_id',
                'cbbDataSource' => ['', 'Report 1', 'Report 2', 'Report 3'],
                'renderer'  => 'dropdown',
                'editable' => true,
                'width' => 100,
            ],
            [
                'title' => 'Letter Header',
                'dataIndex' => 'report_id',
                'cbbDataSource' => ['', 'Letter Header 1', 'Letter Header 2', 'Letter Header 3'],
                'renderer'  => 'dropdown',
                'editable' => true,
                'width' => 100,
            ],
            [
                'title' => 'Letter Footer',
                'dataIndex' => 'report_id',
                'cbbDataSource' => ['', 'Letter Footer 1', 'Letter Footer 2', 'Letter Footer 3'],
                'renderer'  => 'dropdown',
                'editable' => true,
                'width' => 100,
            ],
            [
                'title' => 'Landscape',
                'dataIndex' => 'is_landscape',
                'renderer'  => 'checkbox',
                'align' => 'center',
                'editable' => true,
                'width' => 50,
            ],
            [
                'dataIndex' => 'width',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'height',
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
                'title' => 'Stackable Letter Head',
                'dataIndex' => 'is_stackable_letter_head',
                'renderer'  => 'checkbox',
                'align' => 'center',
                'editable' => true,
                'width' => 50,
            ],
            [
                'title' => 'Full width',
                'dataIndex' => 'is_full_width',
                'renderer'  => 'checkbox',
                'align' => 'center',
                'editable' => true,
                'width' => 50,
            ],
            [
                'dataIndex' => 'order_no',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],



        ];
    }
}
