<?php

namespace App\Http\Controllers\Workflow;

class ManageReportFiltersController extends AbstractManageLibController
{
    protected $title = 'Manage Report Filtering';
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
                'title' => 'Column',
                'dataIndex' => 'column_id',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'title' => 'Report',
                'dataIndex' => 'report_id',
                'renderer'  => 'text4',
                'editable' => true,
            ],
            [
                'dataIndex' => 'col_span',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'title' => 'Blacklist or Whitelist IDs',
                'dataIndex' => 'bw_list_ids',
                'renderer'  => 'dropdown',
                'cbbDataSource' => ['', 'Blacklist', 'Whitelist'],
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'control_type',
                'renderer'  => 'dropdown',
                'ccbDataSource' => ['', 'Datetime', 'Text/Number', 'Dropdown'],
                'editable' => true,
                'width' => 100,
            ],
            [
                'dataIndex' => 'default_value',
                'renderer'  => 'text4',
                'editable' => true,
                'width' => 100,
            ],
            [
                'title' => 'Required',
                'dataIndex' => 'is_required',
                'renderer'  => 'checkbox',
                'align' => 'center',
                'editable' => true,
                'width' => 50,
            ],
            [
                'title' => 'Listen to',
                'dataIndex' => 'has_listen_to',
                'renderer'  => 'checkbox',
                'align' => 'center',
                'editable' => true,
                'width' => 50,
            ],
            [
                'dataIndex' => 'allow_clear',
                'renderer'  => 'checkbox',
                'align' => 'center',
                'editable' => true,
                'width' => 50,
            ],
            [
                'title' => 'Multiple Selection',
                'dataIndex' => 'is_multiple',
                'renderer'  => 'checkbox',
                'align' => 'center',
                'editable' => true,
                'width' => 50,
            ],
        ];
    }
}
