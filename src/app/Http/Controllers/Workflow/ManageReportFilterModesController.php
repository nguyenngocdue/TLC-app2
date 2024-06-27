<?php

namespace App\Http\Controllers\Workflow;

use App\Utils\Support\JsonControls;

class ManageReportFilterModesController extends AbstractManageLibController
{
    protected $title = 'Manage Report Filtering';
    protected $libraryClass = LibReports::class;
    protected $route = 'manageReportFilterModes';
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
                'title' => 'Report',
                'dataIndex' => 'report_id',
                'renderer' => 'dropdown',
                'cbbDataSource' => ['', 'Report 1', 'Report 2', 'Report 3'],
                'editable' => true,
                'width' => 100,
            ],
            [
                'title' => 'Filter Modes',
                'dataIndex' => 'filter_mode',
                'renderer' => 'dropdown',
                'cbbDataSource' => ['', 'Filter 1', 'Filter 2', 'Filter 3'],
                'editable' => true,
            ],

        ];
    }
}
